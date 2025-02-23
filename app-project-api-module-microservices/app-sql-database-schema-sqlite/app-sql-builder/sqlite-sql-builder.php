<?php
namespace App\DatabaseSchema\Sql\Builder;
?>
<?php
use App\DatabaseSchema\Domain\Models\DatabaseSchemaModel;
use App\DatabaseSchema\Domain\Models\TableDataModel;
use App\DatabaseSchema\Domain\Models\ColumnDataModel;
use App\DatabaseSchema\Domain\Models\ColumnKeyModel;
use App\DatabaseSchema\Domain\Models\CompositeKeyModel;
use App\DatabaseSchema\Helper\Data\Finder\DatabaseSchemaDataFinder;
use App\DatabaseSchema\Helper\Key\Type\RelationalKeyType;
use RzSDK\Log\DebugLog;
?>
<?php
class SqliteSqlBuilder {
    private $databaseSchemas;
    private int $maxColumnLength = 0;
    private int $maxColumnPadLength = 0;
    private int $maxDataTypeLength = 0;
    private int $maxDataTypePadLength = 0;
    private array $tableList = array();
    //
    public function buildSql(?array $schemas): string {
        if(empty($schemas)) {
            return "";
        }
        $this->databaseSchemas = $schemas;
        $this->tableList = array();
        $sql = "";

        foreach($schemas as $schema) {
            $sql .= $this->buildSchemaSql($schema);
        }

        return $sql;
    }

    private function buildSchemaSql(DatabaseSchemaModel $schema): string {
        $databaseSchemaDataFinder = new DatabaseSchemaDataFinder(array($schema));
        $this->maxColumnLength = $databaseSchemaDataFinder->getMaxColumnLength($schema->id);
        $this->maxDataTypeLength = $databaseSchemaDataFinder->getMaxDataTypeLength($schema->id);
        $this->maxColumnPadLength = $this->maxColumnLength + 4;
        $this->maxDataTypePadLength = $this->maxDataTypeLength + 4;
        $sql = "\n\n\n\n";
        $sql .= "CREATE DATABASE IF NOT EXISTS {$schema->schemaName};\n";
        $sql .= "USE {$schema->schemaName};\n";
        $sql .= "\n\n\n\n";

        $tableCreateSql = "";
        foreach($schema->tableDataList as $table) {
            $this->tableList[] = $table->tableName;
            $tableCreateSql .= $this->buildTableSql($schema, $table);
        }
        //DebugLog::log($this->tableList);

        $dropTableSql = $this->dropTableSql($schema);

        $deleteTableSql = $this->deleteTableSql($schema);

        $sql .= "{$dropTableSql}\n\n{$tableCreateSql}\n\n{$deleteTableSql}";
        $sql .= "\n\n\n\n";

        return $sql;
    }

    private function dropTableSql(DatabaseSchemaModel $schema): string {
        $dropTableSql = "";
        $tablePrefix = trim($schema->tablePrefix, "_");
        if(!empty($tablePrefix)) {
            $tablePrefix .= "_";
        }
        foreach($this->tableList as $table) {
            $dropTableSql .= "DROP TABLE IF EXISTS {$tablePrefix}{$table};\n";
        }
        return $dropTableSql;
    }

    private function deleteTableSql(DatabaseSchemaModel $schema): string {
        $dropTableSql = "";
        $tablePrefix = trim($schema->tablePrefix, "_");
        if(!empty($tablePrefix)) {
            $tablePrefix .= "_";
        }
        foreach($this->tableList as $table) {
            $dropTableSql .= "DELETE FROM {$tablePrefix}{$table};\n";
        }
        return $dropTableSql;
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
        //echo "<pre>" . print_r($table, true) . "</pre>";
        foreach($table->columnKeyList as $key) {
            //echo "<pre>" . print_r($key, true) . "</pre>";
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
            $sql = "CREATE TABLE IF NOT EXISTS $tablePrefix{$table->tableName} (\n";
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
        $columnName = "{$column->columnName}";
        $columnName =  str_pad($columnName, $this->maxColumnPadLength, " ");
        $dataType = "{$column->dataType}";
        $dataType =  str_pad($dataType, $this->maxDataTypePadLength, " ");
        $sql = "    {$columnName} {$dataType}";
        if($column->isNullable && strtolower($column->isNullable) == "true") {
            $sql .= " NULL";
        } else {
            $sql .= " NOT NULL";
        }
        if($column->haveDefault && strtolower($column->haveDefault) == "true") {
            $sql .= " DEFAULT";
        }
        if($column->defaultValue !== null && $column->defaultValue !== "") {
            $sql .= " {$column->defaultValue}";
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
        //echo "<pre>" . print_r($key, true) . "</pre>";
        $tablePrefix = trim($schema->tablePrefix, "_");
        if(!empty($tablePrefix)) {
            $tablePrefix .= "_";
        }
        $workingTableId = $key->workingTable;
        $workingColumnId = $key->mainColumn;
        $referenceColumnId = $key->referenceColumn;
        //
        $databaseSchemaDataFinder = new DatabaseSchemaDataFinder($this->databaseSchemas);
        $workingColumnInfo = $databaseSchemaDataFinder->getColumnDetails($workingColumnId);
        $referenceColumnInfo = array();
        if(!empty($referenceColumnId)) {
            $referenceColumnInfo = $databaseSchemaDataFinder->getColumnDetails($referenceColumnId);
        }
        //
        $workingTableName = $workingColumnInfo["table"];
        $workingColumnName = $workingColumnInfo["column"];
        $referenceTableName = "";
        $referenceColumnName = "";
        if(!empty($referenceColumnInfo)) {
            $referenceTableName = $referenceColumnInfo["table"];
            $referenceColumnName = $referenceColumnInfo["column"];
            $this->onRearrangeDropTableList($workingTableName, $referenceTableName);
        }
        //
        $workingColumnKey = $workingColumnName;
        $referenceColumnKey = $referenceColumnName;
        if(!empty($key->compositeKeyList)) {
            $compositeColumns = $this->getCompositeKeyList($key->compositeKeyList);
            //DebugLog::log($compositeColumns);
            if(!empty($compositeColumns)) {
                if(!empty($compositeColumns["primary"])) {
                    $workingColumnName = $workingColumnName . ", ". implode(", ", $compositeColumns["primary"]);
                    $workingColumnKey = $workingColumnKey . "_". implode("_", $compositeColumns["primary"]);
                    $workingColumnName = trim($workingColumnName);
                    $workingColumnKey = trim($workingColumnKey);
                }
            }
            if(!empty($compositeColumns)) {
                if(!empty($compositeColumns["reference"])) {
                    $referenceColumnName = $referenceColumnName . ", ". implode(", ", $compositeColumns["reference"]);
                }
            }
        }
        //
        $sql = "    ";
        $relationalKeyType = RelationalKeyType::getByName($key->keyType);
        if(!empty($relationalKeyType)) {
            if($relationalKeyType == RelationalKeyType::PRIMARY) {
                $sql .= "CONSTRAINT pk_{$workingTableName}_{$workingColumnKey} PRIMARY KEY($workingColumnName)";
            } else if($relationalKeyType == RelationalKeyType::UNIQUE) {
                $sql .= "CONSTRAINT uk_{$workingTableName}_{$workingColumnKey} UNIQUE($workingColumnName)";
            } else if($relationalKeyType == RelationalKeyType::FOREIGN) {
                //$rand = rand(1, 100);
                $sql .= "CONSTRAINT fk_{$workingTableName}_{$workingColumnKey}_{$referenceTableName}_{$referenceColumnKey} FOREIGN KEY($workingColumnName) REFERENCES {$tablePrefix}{$referenceTableName}($referenceColumnName)";
            }
        }
        /*if($key->keyType === "PRIMARY") {
            $sql .= "PRIMARY KEY";
        } elseif($key->keyType === "FOREIGN") {
            $sql .= "FOREIGN KEY (`{$key->mainColumnName}`) REFERENCES `{$key->referenceTableName}`(`{$key->referenceColumnName}`)";
        } elseif($key->keyType === "UNIQUE") {
            $sql .= "UNIQUE KEY `{$key->uniqueName}` (`{$key->mainColumnName}`)";
        }*/
        /*echo $sql;
        echo "<br />";*/
        return trim($sql);
    }

    private function getCompositeKeyList(?array $keyList): array {
        //DebugLog::log($key);
        $compositeKeyList = array(
            "primary" => array(),
            "reference" => array(),
        );
        $databaseSchemaDataFinder = new DatabaseSchemaDataFinder($this->databaseSchemas);
        foreach($keyList as $key) {
            $primaryId = $key->primaryColumn;
            $compositeId = $key->compositeColumn;
            if(!empty($primaryId)) {
                $primaryColumnInfo = $databaseSchemaDataFinder->getColumnDetails($primaryId);
                if(!empty($primaryColumnInfo)) {
                    $compositeKeyList["primary"][] = $primaryColumnInfo["column"];
                }
            }
            if(!empty($compositeId)) {
                $compositeColumnInfo = $databaseSchemaDataFinder->getColumnDetails($compositeId);
                if(!empty($compositeColumnInfo)) {
                    $compositeKeyList["reference"][] = $compositeColumnInfo["column"];
                }
            }
        }
        return $compositeKeyList;
    }

    private function onRearrangeDropTableList($mainTable, $referenceTable) {
        if(empty($mainTable) || empty($referenceTable)) {
            return;
        }
        $workingIndex = -1;
        $referenceIndex = -1;
        //foreach($this->tableList as $table) {}
        foreach($this->tableList as $key => $value) {
            if($value === $mainTable) {
                $workingIndex = $key;
            }
            if($value === $referenceTable) {
                $referenceIndex = $key;
            }
        }
        $this->tableList[$workingIndex] = $referenceTable;
        $this->tableList[$referenceIndex] = $mainTable;
    }
}
?>
