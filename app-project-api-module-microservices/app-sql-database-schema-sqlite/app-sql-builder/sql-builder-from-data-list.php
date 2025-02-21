<?php
namespace App\DatabaseSchema\Sql\Builder;
?>
<?php
use App\DatabaseSchema\Domain\Models\DatabaseSchemaModel;
use App\DatabaseSchema\Domain\Models\TableDataModel;
use App\DatabaseSchema\Domain\Models\ColumnDataModel;
use App\DatabaseSchema\Domain\Models\ColumnKeyModel;
use App\DatabaseSchema\Helper\Data\Finder\DatabaseSchemaDataFinder;
use App\DatabaseSchema\Helper\Key\Type\RelationalKeyType;
use RzSDK\Log\DebugLog;
?>
<?php
class SqlBuilderFromDataList {
    private $databaseSchemas;
    private int $maxColumnLength;
    private int $maxPadLength = 0;
    //
    public function buildSql(array $schemas): string {
        $this->databaseSchemas = $schemas;
        $sql = "";

        foreach($schemas as $schema) {
            $sql .= $this->buildSchemaSql($schema);
        }

        return $sql;
    }

    private function buildSchemaSql(DatabaseSchemaModel $schema): string {
        $databaseSchemaDataFinder = new DatabaseSchemaDataFinder(array($schema));
        $this->maxColumnLength = $databaseSchemaDataFinder->getMaxColumnLength($schema->id);
        $this->maxPadLength = $this->maxColumnLength + 4;
        $sql = "CREATE DATABASE IF NOT EXISTS `{$schema->schemaName}`;\n"; // Create database if not exists
        $sql .= "USE `{$schema->schemaName}`;\n"; // Use the specified database
        $sql .= "\n\n\n\n";

        foreach($schema->tableDataList as $table) {
            $sql .= $this->buildTableSql($schema, $table);
        }

        return $sql;
    }

    private function buildTableSql(DatabaseSchemaModel $schema, TableDataModel $table): string {
        $tablePrefix = trim($schema->tablePrefix, "_");
        if(!empty($tablePrefix)) {
            $tablePrefix .= "_";
        }

        $sql = "";
        //$sql = "CREATE TABLE IF NOT EXISTS `$tablePrefix{$table->tableName}` (\n";
        $sqlCommands = array();
        $columnsSql = array();

        foreach($table->columnDataList as $column) {
            $data = $this->buildColumnSql($column);
            if(!empty($data)) {
                $columnsSql[] = $data;
            }
        }

        if(!empty($columnsSql)) {
            $sqlCommands = array_merge($sqlCommands, $columnsSql);
        }

        //$sql .= implode(",\n", $columnsSql) . "\n";

        $keysSql = array();
        $columnKeyList = array();
        $columnKeyFkList = array();
        $columnKeyPkList = array();
        $columnKeyUkList = array();
        foreach($table->columnKeyList as $key) {
            $relationalKeyType = RelationalKeyType::getByName($key->keyType);
            if(!empty($relationalKeyType)) {
                if($relationalKeyType == RelationalKeyType::PRIMARY) {
                    $columnKeyPkList[] = $key;
                } else if($relationalKeyType == RelationalKeyType::FOREIGN) {
                    $columnKeyFkList[] = $key;
                } else if($relationalKeyType == RelationalKeyType::UNIQUE) {
                    $columnKeyUkList[] = $key;
                }
            }
        }
        $columnKeyList = array_merge($columnKeyPkList, $columnKeyUkList, $columnKeyFkList);
        foreach($columnKeyList as $key) {
            $data = $this->buildKeySql($schema, $table, $key);
            //$keysSql[] = $this->buildKeySql($table, $key);
            if(!empty($data)) {
                $keysSql[] = "    {$data}";
            }
        }
        if(!empty($keysSql)) {
            $sqlCommands = array_merge($sqlCommands, $keysSql);
            /*foreach($keysSql as $key => $value) {
                if(!empty($value)) {
                    $sqlCommands[$key] = trim($value);
                }
            }*/
        }
        /*if(!empty($keysSql)) {
            $sql .= ",\n".implode(",\n", $keysSql)."\n";
        }*/

        if(!empty($sqlCommands)) {
            /*foreach($sqlCommands as $key => $value) {
                if(!empty($value)) {
                    $sqlCommands[$key] = trim($value);
                }
            }*/
            $sql = "CREATE TABLE IF NOT EXISTS `$tablePrefix{$table->tableName}` (\n";
            $sql .= implode(",\n", $sqlCommands) . "\n";
            $sql .= ");\n\n";
        }


        //$sql .= ");\n\n";
        return $sql;
    }

    private function buildColumnSql(ColumnDataModel $column): string {
        if(empty($column->columnName)) {
            return "";
        }
        $columnName = "`{$column->columnName}`";
        $columnName =  str_pad($columnName, $this->maxPadLength, " ");
        $sql = "    {$columnName} {$column->dataType}";
        if(!$column->isNullable) {
            $sql .= " NOT NULL";
        }
        if($column->defaultValue !== null && $column->defaultValue !== "") { // Check for null or empty string
            $sql .= " DEFAULT {$column->defaultValue}";
        }
        if($column->columnComment) {
            $sql .= " COMMENT '{$column->columnComment}'";
        }

        return $sql;
    }

    private function buildKeySql(DatabaseSchemaModel $schema, TableDataModel $table, ColumnKeyModel $key): string {
        if(empty($key->keyType)) {
            return "";
        }
        $tablePrefix = trim($schema->tablePrefix, "_");
        if(!empty($tablePrefix)) {
            $tablePrefix .= "_";
        }
        $workingTableId = $key->workingTable;
        $workingColumnId = $key->mainColumn;
        $referenceColumnId = $key->referenceColumn;
        //
        $databaseSchemaDataFinder = new DatabaseSchemaDataFinder($this->databaseSchemas);
        $workingTableInfo = $databaseSchemaDataFinder->getColumnDetails($workingColumnId);
        $referenceColumnInfo = array();
        if(!empty($referenceColumnId)) {
            $referenceColumnInfo = $databaseSchemaDataFinder->getColumnDetails($referenceColumnId);
        }
        //
        $workingTableName = $workingTableInfo["table"];
        $workingColumnName = $workingTableInfo["column"];
        $referenceTableName = "";
        $referenceColumnName = "";
        if(!empty($referenceColumnInfo)) {
            $referenceTableName = $referenceColumnInfo["table"];
            $referenceColumnName = $referenceColumnInfo["column"];
        }
        //
        $sql = "    ";
        $relationalKeyType = RelationalKeyType::getByName($key->keyType);
        if(!empty($relationalKeyType)) {
            if($relationalKeyType == RelationalKeyType::PRIMARY) {
                $sql .= "CONSTRAINT pk_{$workingTableName}_{$workingColumnName} PRIMARY KEY($workingColumnName)";
            } else if($relationalKeyType == RelationalKeyType::UNIQUE) {
                $sql .= "CONSTRAINT uk_{$workingTableName}_{$workingColumnName} UNIQUE($workingColumnName)";
            } else if($relationalKeyType == RelationalKeyType::FOREIGN) {
                $sql .= "CONSTRAINT fk_{$workingTableName}_{$workingColumnName}_{$referenceTableName}_{$referenceColumnName} FOREIGN KEY($workingColumnName) REFERENCES {$tablePrefix}{$referenceTableName}($referenceColumnName)";
            }
        }
        /*if($key->keyType === "PRIMARY") {
            $sql .= "PRIMARY KEY";
        } elseif($key->keyType === "FOREIGN") {
            $sql .= "FOREIGN KEY (`{$key->mainColumnName}`) REFERENCES `{$key->referenceTableName}`(`{$key->referenceColumnName}`)";
        } elseif($key->keyType === "UNIQUE") {
            $sql .= "UNIQUE KEY `{$key->uniqueName}` (`{$key->mainColumnName}`)";
        }*/
        return trim($sql);
    }
}
?>
