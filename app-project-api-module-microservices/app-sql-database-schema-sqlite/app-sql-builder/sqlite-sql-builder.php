<?php
declare(strict_types=1);

namespace App\DatabaseSchema\Sql\Builder;

use App\DatabaseSchema\Domain\Models\DatabaseSchemaModel;
use App\DatabaseSchema\Domain\Models\TableDataModel;
use App\DatabaseSchema\Domain\Models\ColumnDataModel;
use App\DatabaseSchema\Domain\Models\ColumnKeyModel;
use App\DatabaseSchema\Helper\Data\Finder\DatabaseSchemaDataFinder;
use App\DatabaseSchema\Helper\Key\Type\RelationalKeyType;
use RzSDK\Log\DebugLog;

class SqliteSqlBuilder {
    private array $databaseSchemas = [];
    private int $maxColumnLength = 0;
    private int $maxColumnPadLength = 0;
    private int $maxDataTypeLength = 0;
    private int $maxDataTypePadLength = 0;
    private array $createTableList = [];
    private array $dropTableList = [];

    public function buildSql(?array $schemas): string {
        if(empty($schemas)) {
            return "";
        }

        $this->databaseSchemas = $schemas;
        $this->createTableList = [];
        $this->dropTableList = [];
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

        foreach($schema->tableDataList as $table) {
            $this->createTableList[$table->tableName] = "";
            $this->dropTableList[$table->tableName] = $table->tableName;
        }

        $sql = "\n\n\n\n";
        $sql .= "CREATE DATABASE IF NOT EXISTS {$schema->schemaName};\n";
        $sql .= "USE {$schema->schemaName};\n";
        $sql .= "\n\n\n\n";

        $tableCreateSql = "";
        foreach($schema->tableDataList as $table) {
            $tableCreateSql = $this->buildTableSql($schema, $table);
            $this->createTableList[$table->tableName] = $tableCreateSql;
        }

        $dropTableSql = $this->generateDropTableSql($schema);
        $deleteTableSql = $this->generateDeleteTableSql($schema);
        $tableCreateSql = implode("", $this->createTableList);

        $sql .= "{$dropTableSql}\n\n{$tableCreateSql}\n\n{$deleteTableSql}";
        $sql .= "\n\n\n\n";

        return $sql;
    }

    private function generateDropTableSql(DatabaseSchemaModel $schema): string {
        $dropTableSql = "";
        $tablePrefix = $this->getTablePrefix($schema);

        foreach($this->dropTableList as $table) {
            $dropTableSql .= "DROP TABLE IF EXISTS {$tablePrefix}{$table};\n";
        }

        return $dropTableSql;
    }

    private function generateDeleteTableSql(DatabaseSchemaModel $schema): string {
        $deleteTableSql = "";
        $tablePrefix = $this->getTablePrefix($schema);

        foreach($this->dropTableList as $table) {
            $deleteTableSql .= "DELETE FROM {$tablePrefix}{$table};\n";
        }

        return $deleteTableSql;
    }

    private function buildTableSql(DatabaseSchemaModel $schema, TableDataModel $table): string {
        $tablePrefix = $this->getTablePrefix($schema);
        $sqlCommands = [];

        foreach($table->columnDataList as $column) {
            $columnSql = $this->buildColumnSql($column);
            if(!empty($columnSql)) {
                $sqlCommands[] = $columnSql;
            }
        }

        $keysSql = $this->buildKeysSql($schema, $table);
        if(!empty($keysSql)) {
            $sqlCommands = array_merge($sqlCommands, $keysSql);
        }

        if(!empty($sqlCommands)) {
            $sql = "CREATE TABLE IF NOT EXISTS $tablePrefix{$table->tableName} (\n";
            $sql .= implode(",\n", $sqlCommands) . "\n";
            $sql .= ");\n\n";
            return $sql;
        }

        return "";
    }

    private function buildColumnSql(ColumnDataModel $column): string {
        if(empty($column->columnName)) {
            return "";
        }

        $columnName = str_pad($column->columnName, $this->maxColumnPadLength, " ");
        $dataType = str_pad($column->dataType, $this->maxDataTypePadLength, " ");
        $sql = "    {$columnName} {$dataType}";

        $sql .= $column->isNullable && strtolower($column->isNullable) === "true" ? " NULL" : " NOT NULL";

        if($column->haveDefault && strtolower($column->haveDefault) === "true") {
            $sql .= " DEFAULT";
            if($column->defaultValue !== null && $column->defaultValue !== "") {
                $sql .= " {$column->defaultValue}";
            } else {
                $sql .= $this->getDefaultValueForDataType($column->dataType);
            }
        }

        if($column->columnComment) {
            $sql .= " COMMENT '{$column->columnComment}'";
        }

        return $sql;
    }

    private function getDefaultValueForDataType(string $dataType): string {
        $dataType = strtolower(substr($dataType, 0, 2));
        return $dataType === "in" ? " 0" : ($dataType === "bo" ? " FALSE" : "");
    }

    private function buildKeysSql(DatabaseSchemaModel $schema, TableDataModel $table): array {
        $keysSql = [];
        $columnKeyList = $this->categorizeColumnKeys($table->columnKeyList);

        foreach($columnKeyList as $key) {
            $keySql = $this->buildKeySql($schema, $table, $key);
            if(!empty($keySql)) {
                $keysSql[] = "    {$keySql}";
            }
        }

        return $keysSql;
    }

    private function categorizeColumnKeys(array $columnKeyList): array {
        $columnKeyPkList = [];
        $columnKeyFkList = [];
        $columnKeyUkList = [];

        foreach ($columnKeyList as $key) {
            $relationalKeyType = RelationalKeyType::getByName($key->keyType);
            if($relationalKeyType === RelationalKeyType::PRIMARY) {
                $columnKeyPkList[] = $key;
            } elseif($relationalKeyType === RelationalKeyType::FOREIGN) {
                $columnKeyFkList[] = $key;
            } elseif($relationalKeyType === RelationalKeyType::UNIQUE) {
                $columnKeyUkList[] = $key;
            }
        }

        return array_merge($columnKeyPkList, $columnKeyUkList, $columnKeyFkList);
    }

    private function buildKeySql(DatabaseSchemaModel $schema, TableDataModel $table, ColumnKeyModel $key): string {
        if(empty($key->keyType)) {
            return "";
        }

        $tablePrefix = $this->getTablePrefix($schema);
        $databaseSchemaDataFinder = new DatabaseSchemaDataFinder($this->databaseSchemas);

        $workingColumnInfo = $databaseSchemaDataFinder->getColumnDetails($key->mainColumn);
        $referenceColumnInfo = $key->referenceColumn ? $databaseSchemaDataFinder->getColumnDetails($key->referenceColumn) : [];

        $workingTableName = $workingColumnInfo["table"];
        $workingColumnName = $workingColumnInfo["column"];
        $referenceTableName = $referenceColumnInfo["table"] ?? "";
        $referenceColumnName = $referenceColumnInfo["column"] ?? "";

        if($referenceTableName && $referenceColumnName) {
            $this->rearrangeCreateTableList($workingTableName, $referenceTableName);
            $this->rearrangeDropTableList($workingTableName, $referenceTableName);
        }

        $workingColumnKey = $this->buildCompositeKey($key, $workingColumnName, "primary");
        $referenceColumnKey = $this->buildCompositeKey($key, $referenceColumnName, "reference");

        $relationalKeyType = RelationalKeyType::getByName($key->keyType);
        if(!$relationalKeyType) {
            return "";
        }

        switch($relationalKeyType) {
            case RelationalKeyType::PRIMARY:
                return "CONSTRAINT pk_{$workingTableName}_{$workingColumnKey} PRIMARY KEY($workingColumnName)";
            case RelationalKeyType::UNIQUE:
                return "CONSTRAINT uk_{$workingTableName}_{$workingColumnKey} UNIQUE($workingColumnName)";
            case RelationalKeyType::FOREIGN:
                return "CONSTRAINT fk_{$workingTableName}_{$workingColumnKey}_{$referenceTableName}_{$referenceColumnKey} FOREIGN KEY($workingColumnName) REFERENCES {$tablePrefix}{$referenceTableName}($referenceColumnName)";
            default:
                return "";
        }
    }

    private function buildCompositeKey(ColumnKeyModel $key, string $columnName, string $type): string {
        if(empty($key->compositeKeyList)) {
            return $columnName;
        }

        $compositeColumns = $this->getCompositeKeyList($key->compositeKeyList);
        $compositeColumnNames = $compositeColumns[$type] ?? [];

        if(!empty($compositeColumnNames)) {
            $columnName .= ", " . implode(", ", $compositeColumnNames);
        }

        return trim($columnName);
    }

    private function getCompositeKeyList(?array $keyList): array {
        $compositeKeyList = [
            "primary" => [],
            "reference" => [],
        ];

        $databaseSchemaDataFinder = new DatabaseSchemaDataFinder($this->databaseSchemas);
        foreach ($keyList as $key) {
            if($key->primaryColumn) {
                $primaryColumnInfo = $databaseSchemaDataFinder->getColumnDetails($key->primaryColumn);
                if($primaryColumnInfo) {
                    $compositeKeyList["primary"][] = $primaryColumnInfo["column"];
                }
            }
            if($key->compositeColumn) {
                $compositeColumnInfo = $databaseSchemaDataFinder->getColumnDetails($key->compositeColumn);
                if($compositeColumnInfo) {
                    $compositeKeyList["reference"][] = $compositeColumnInfo["column"];
                }
            }
        }

        return $compositeKeyList;
    }

    private function rearrangeCreateTableList(string $mainTable, string $referenceTable): void {
        if (empty($mainTable) || empty($referenceTable)) {
            return;
        }

        $workingIndex = array_search($mainTable, array_keys($this->createTableList));
        $referenceIndex = array_search($referenceTable, array_keys($this->createTableList));

        if($referenceIndex > $workingIndex) {
            $tempTableList = [];
            foreach($this->createTableList as $key => $value) {
                if($key === $mainTable) {
                    $tempTableList[$referenceTable] = $this->createTableList[$referenceTable];
                }
                $tempTableList[$key] = $value;
            }
            $this->createTableList = $tempTableList;
        }
    }

    private function rearrangeDropTableList(string $mainTable, string $referenceTable): void {
        if(empty($mainTable) || empty($referenceTable)) {
            return;
        }

        $workingIndex = array_search($mainTable, array_keys($this->dropTableList));
        $referenceIndex = array_search($referenceTable, array_keys($this->dropTableList));

        if ($workingIndex !== false && $referenceIndex !== false) {
            if($workingIndex > $referenceIndex) {
                $tempTableList = [];
                foreach($this->dropTableList as $key => $value) {
                    if($key === $referenceTable) {
                        $tempTableList[$mainTable] = $this->dropTableList[$mainTable];
                    }
                    $tempTableList[$key] = $value;
                }
                $this->dropTableList = $tempTableList;
            }
        }
    }

    private function getTablePrefix(DatabaseSchemaModel $schema): string {
        $tablePrefix = trim($schema->tablePrefix, "_");
        return $tablePrefix ? "{$tablePrefix}_" : "";
    }
}