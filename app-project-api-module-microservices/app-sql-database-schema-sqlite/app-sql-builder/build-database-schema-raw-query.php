<?php
namespace App\DatabaseSchema\Schema\Build\RawQuery;
?>
<?php
use App\DatabaseSchema\Helper\Database\Data\Retrieve\DatabaseSchemaRawQuery;
use App\DatabaseSchema\Domain\Models\DatabaseSchemaModel;
use App\DatabaseSchema\Domain\Models\TableDataModel;
use App\DatabaseSchema\Domain\Models\ColumnDataModel;
use App\DatabaseSchema\Domain\Models\ColumnKeyModel;
use App\DatabaseSchema\Domain\Models\CompositeKeyModel;
use App\DatabaseSchema\Data\Entities\DatabaseSchema;
use App\DatabaseSchema\Data\Entities\TableData;
use App\DatabaseSchema\Data\Entities\ColumnData;
use App\DatabaseSchema\Data\Entities\ColumnKey;
use App\DatabaseSchema\Data\Entities\CompositeKey;
use App\DatabaseSchema\Data\Mappers\DatabaseSchemaMapper;
use App\DatabaseSchema\Data\Mappers\TableDataMapper;
use App\DatabaseSchema\Data\Mappers\ColumnDataMapper;
use App\DatabaseSchema\Data\Mappers\ColumnKeyMapper;
use App\DatabaseSchema\Data\Mappers\CompositeKeyMapper;
use App\DatabaseSchema\Helper\Database\Data\Retrieve\DbRetrieveDatabaseSchemaData;
use RzSDK\Log\DebugLog;
?>
<?php
class BuildDatabaseSchemaRawQuery {
    private $outputCompositeKeyList = array();
    public function buildDatabaseSchemaRawQuery($schemaId = "", $tableId = "", $notTableId = "") {
        $outputDataList = array(
            "schema" => [],
            "data" => "",
        );
        $schemaRawQuery = new DatabaseSchemaRawQuery();
        $rawQueryOutput = $schemaRawQuery->getDatabaseSchema($schemaId, $tableId, $notTableId);
        //DebugLog::log($rawQueryOutput);
        $outputDataList["schema"][] = "";
        $tempOutputData = "-- SQLite Database";
        if(!empty($rawQueryOutput["schema"])) {
            //DebugLog::log($rawQueryOutput);
            $outputDataList["schema"][] = "\n\n";
            $outputDataList["schema"][] = "-- Database Schema: Database Schema";
            $outputDataList["schema"][] = "\n";
            foreach($rawQueryOutput["schema"] as $schemaItem) {
                $tempOutputData .= " DATE CREATED: " . date("Y-m-d", strtotime($schemaItem->createdDate));
                $tempOutputData .= ", DATE MODIFIED: " . date("Y-m-d", );
                $tempOutputData .= " - VERSION: v-{$schemaItem->schemaVersion}";
                $tempOutputData .= "\n";
                $tempOutputData .= "-- DATABASE NAME: " . ucwords(str_replace("_", " ", $schemaItem->schemaName));
                //DebugLog::log($rawQuery);
                //DebugLog::log($rawQuery->id);
                //$databaseSchema = DatabaseSchemaMapper::toEntity($rawQuery);
                $databaseSchema = DatabaseSchemaMapper::toDomain($schemaItem);
                $sqlQuery = $this->buildInsertQuery($databaseSchema, "tbl_database_schema");
                //DebugLog::log($databaseSchema);
                $outputDataList["schema"][] = $sqlQuery;
                $outputDataList["schema"][] = "\n";
                $tableDataSqlList = $this->buildTableDataRawQuery($schemaItem->tableDataList);
                if(!empty($tableDataSqlList)) {
                    $outputDataList["schema"][] = "\n";
                    $outputDataList["schema"][] = "-- Database Schema: Table Schema";
                    $outputDataList["schema"][] = "\n";
                    $outputDataList["schema"] = array_merge($outputDataList["schema"], $tableDataSqlList);
                }
            }
            //DebugLog::log($outputDataList);
        }
        $outputDataList["schema"][0] = $tempOutputData;
        $outputDataList["data"] = implode("", $outputDataList["schema"]);
        return $outputDataList;
    }

    public function buildTableDataRawQuery($tableDataList) {
        //DebugLog::log($tableDataList);
        $outputDataList = array();
        $outputColumnDataList = array();
        $outputColumnKeyList = array();
        if(!empty($tableDataList)) {
            foreach($tableDataList as $tableItem) {
                //$tableData = TableDataMapper::toEntity($tableItem);
                $tableData = TableDataMapper::toDomain($tableItem);
                //DebugLog::log($tableData);
                $sqlQuery = $this->buildInsertQuery($tableData, "tbl_table_data");
                //DebugLog::log($sqlQuery);
                $outputDataList[] = $sqlQuery;
                $outputDataList[] = "\n";
                $columnDataSqlList = $this->buildColumnDataRawQuery($tableItem->columnDataList);
                if(!empty($columnDataSqlList)) {
                    $outputColumnDataList = array_merge($outputColumnDataList, $columnDataSqlList);
                    /*$outputDataList["schema"][] = "\n";
                    $outputDataList["schema"][] = "-- Database Schema: Column Schema";
                    $outputDataList["schema"][] = "\n";
                    $outputDataList = array_merge($outputDataList, $columnDataSqlList);*/
                }
                $columnKeySqlList = $this->buildColumnKeyRawQuery($tableItem->columnKeyList);
                //DebugLog::log($columnKeySqlList);
                if(!empty($columnKeySqlList)) {
                    $outputColumnKeyList = array_merge($outputColumnKeyList, $columnKeySqlList);
                }
            }
            if(!empty($outputColumnDataList)) {
                $outputDataList[] = "\n";
                $outputDataList[] = "-- Database Schema: Column Schema";
                $outputDataList[] = "\n";
                $outputDataList = array_merge($outputDataList, $outputColumnDataList);
            }
            if(!empty($outputColumnKeyList)) {
                $outputDataList[] = "\n";
                $outputDataList[] = "-- Database Schema: Column Key Schema";
                $outputDataList[] = "\n";
                $outputDataList = array_merge($outputDataList, $outputColumnKeyList);
            }
            if(!empty($this->outputCompositeKeyList)) {
                //$outputDataList[] = "\n";
                $outputDataList[] = "-- Database Schema: Composite Key Schema";
                $outputDataList[] = "\n";
                $outputDataList = array_merge($outputDataList, $this->outputCompositeKeyList);
            }
        }
        return $outputDataList;
    }

    public function buildColumnDataRawQuery($columnDataList) {
        //DebugLog::log($columnDataList);
        $outputDataList = array();
        if(!empty($columnDataList)) {
            foreach($columnDataList as $columnItem) {
                //$columnData = ColumnDataMapper::toEntity($columnItem);
                $columnData = ColumnDataMapper::toDomain($columnItem);
                //DebugLog::log($columnData);
                $sqlQuery = $this->buildInsertQuery($columnData, "tbl_column_data");
                //DebugLog::log($sqlQuery);
                $outputDataList[] = $sqlQuery;
                $outputDataList[] = "\n";
            }
            $outputDataList[] = "\n";
        }
        return $outputDataList;
    }

    public function buildColumnKeyRawQuery($columnKeyList) {
        //DebugLog::log($columnKeyList);
        $outputDataList = array();
        $outputCompositeDataList = array();
        if(!empty($columnKeyList)) {
            foreach($columnKeyList as $columnKeyItem) {
                //$columnKeyData = ColumnKeyMapper::toEntity($columnKeyItem);
                $columnKeyData = ColumnKeyMapper::toDomain($columnKeyItem);
                //DebugLog::log($columnKeyData);
                $sqlQuery = $this->buildInsertQuery($columnKeyData, "tbl_column_key");
                //DebugLog::log($sqlQuery);
                $outputDataList[] = $sqlQuery;
                $outputDataList[] = "\n";
                $compositeKeySqlList = $this->buildCompositeKeyRawQuery($columnKeyItem->compositeKeyList);
                if(!empty($compositeKeySqlList)) {
                    $outputCompositeDataList = array_merge($outputCompositeDataList, $compositeKeySqlList);
                }
            }
            $outputDataList[] = "\n";
            if(!empty($compositeKeySqlList)) {
                $outputDataList[] = "\n";
                $outputDataList[] = "-- Database Schema: Composite Key Schema";
                $outputDataList[] = "\n";
                $outputDataList = array_merge($outputDataList, $compositeKeySqlList);
            }
        }
        return $outputDataList;
    }

    public function buildCompositeKeyRawQuery($columnKeyList) {
        //DebugLog::log($columnKeyList);
        $outputDataList = array();
        //CompositeKeyMapper
        if(!empty($columnKeyList)) {
            foreach($columnKeyList as $columnKeyItem) {
                //$columnKeyData = CompositeKeyMapper::toEntity($columnKeyItem);
                $columnKeyData = CompositeKeyMapper::toDomain($columnKeyItem);
                //DebugLog::log($columnKeyData);
                $sqlQuery = $this->buildInsertQuery($columnKeyData, "tbl_composite_key");
                //DebugLog::log($sqlQuery);
                $this->outputCompositeKeyList[] = $sqlQuery;
                $this->outputCompositeKeyList[] = "\n";
            }
            //$this->outputCompositeKeyList[] = "\n";
        }
        return $outputDataList;
    }

    private function buildInsertQuery($arrayData, $tableName) {
        $tableDataList = array();
        foreach($arrayData as $key => $value) {
            $tempValue = $value;
            if(is_bool($value)) {
                $tempValue = $value ? "'TRUE'" : "'FALSE'";
            } else if(is_numeric($value) || is_int($value) || is_integer($value)) {
                $tempValue = $value ? $value : 0;
            } else if(empty($value)) {
                $tempValue = "NULL";
            } else {
                if(strtolower($value) == "false") {
                    $tempValue = "'FALSE'";
                } else if(strtolower($value) == "true") {
                    $tempValue = "'TRUE'";
                } else {
                    //$tempValue = "'" . addslashes($value) . "'";
                    $tempValue = "'" . str_replace("'", "''", $value) . "'";
                    //$tempValue = "'{$value}'";
                }
            }
            $tableDataList[$key] = $tempValue;
        }
        //DebugLog::log($tableDataList);
        /*$keys = array_keys($tableDataList);
        $values = array_values($tableDataList);
        $keys = implode(", ", $keys);
        $values = implode(", ", $values);*/
        $keys = implode(", ", array_keys($tableDataList));
        $values = implode(", ", array_values($tableDataList));
        //
        $sqlString = "INSERT INTO {$tableName} (%s) VALUES (%s);";
        $sqlQuery = sprintf($sqlString, $keys, $values);
        //DebugLog::log($sqlQuery);
        return $sqlQuery;
    }

    public function buildRecursiveDatabaseSchemaRawQuery($schemaId = "", $tableId = "", $notTableId = "") {
        $schemaRawQuery = new DatabaseSchemaRawQuery();
        $rawQueryOutput = $schemaRawQuery->getDatabaseSchema($schemaId, $tableId, $notTableId, function ($item, $depth, $isParent, $isParentClose = false) {
            $data = "";
            //DebugLog::log($item);
            /*if($item instanceof DatabaseSchemaModel) {
                $data .= $this->getDatabaseSchemaRawQuery($item, $depth, $isParent, $isParentClose);
            } else if($item instanceof TableDataModel) {
                $data .= $this->getTableDataRawQuery($item, $depth, $isParent, $isParentClose);
            }*/
            $data .= $this->getRecursiveDatabaseSchemaRawQuery($item, $depth, $isParent, $isParentClose);
            $data .= $this->getRecursiveTableDataRawQuery($item, $depth, $isParent, $isParentClose);
            $data .= $this->getRecursiveColumnDataRawQuery($item, $depth, $isParent, $isParentClose);
            $data .= $this->getRecursiveColumnKeyRawQuery($item, $depth, $isParent, $isParentClose);
            $data .= $this->getRecursiveCompositeKeyRawQuery($item, $depth, $isParent, $isParentClose);
            return $data;
        });
        return $rawQueryOutput;
    }

    private function getRecursiveDatabaseSchemaRawQuery($item, $depth = 0, $isParent = false, $isParentClose = false) {
        $data = "";
        if($item instanceof DatabaseSchemaModel) {
            $databaseSchema = new DatabaseSchema();
            $tempData = "INSERT INTO tbl_database_schema"
                . "({$databaseSchema->id}, {$databaseSchema->schema_name}, {$databaseSchema->schema_version}, {$databaseSchema->table_prefix}, {$databaseSchema->database_comment}, {$databaseSchema->modified_date}, {$databaseSchema->created_date})"
                . " VALUES"
                . "('{$item->id}', '{$item->schemaName}', '{$item->schemaVersion}', '{$item->tablePrefix}', '{$item->databaseComment}', '{$item->modifiedDate}', '{$item->createdDate}');";
            if($isParent) {
                $data .= "{$tempData}\n";
            } else {
                if($isParentClose) {
                    //$data .= "</ul>\n{$indent}</li>\
                    //$data .= "\n";
                } else {
                    $data .= "{$tempData}\n";
                }
            }
        }
        return "{$data}";
    }

    private function getRecursiveTableDataRawQuery($item, $depth = 0, $isParent = false, $isParentClose = false) {
        $data = "";
        if($item instanceof TableDataModel) {
            $tempTableData = new TableData();
            $tempData = "INSERT INTO tbl_table_data"
                . "({$tempTableData->schema_id}, {$tempTableData->id}, {$tempTableData->table_order}, {$tempTableData->table_name}, {$tempTableData->table_comment}, {$tempTableData->column_prefix}, {$tempTableData->modified_date}, {$tempTableData->created_date})"
                . " VALUES"
                . "('{$item->schemaId}', '{$item->id}', '{$item->tableOrder}', '{$item->tableName}', '{$item->tableComment}', '{$item->columnPrefix}', '{$item->modifiedDate}', '{$item->createdDate}');";
            if($isParent) {
                $data .= "{$tempData}\n";
            } else {
                if($isParentClose) {
                    //$data .= "</ul>\n{$indent}</li>\
                    $data .= "\n";
                } else {
                    $data .= "{$tempData}\n";
                }
            }
        }
        return "{$data}";
    }

    private function getRecursiveColumnDataRawQuery($item, $depth = 0, $isParent = false, $isParentClose = false) {
        $data = "";
        if($item instanceof ColumnDataModel) {
            $tempColumnData = new ColumnData();
            $tempData = "INSERT INTO tbl_column_data"
                . "({$tempColumnData->table_id}, {$tempColumnData->id}, {$tempColumnData->column_order}, {$tempColumnData->column_name}, {$tempColumnData->data_type}, {$tempColumnData->is_nullable}, {$tempColumnData->have_default}, {$tempColumnData->default_value}, {$tempColumnData->column_comment}, {$tempColumnData->modified_date}, {$tempColumnData->created_date})"
                . " VALUES"
                . "('{$item->tableId}', '{$item->id}', '{$item->columnOrder}', '{$item->columnName}', '{$item->dataType}', '{$item->isNullable}', '{$item->haveDefault}', '{$item->defaultValue}', '{$item->columnComment}', '{$item->modifiedDate}', '{$item->createdDate}');";
            if($isParent) {
                $data .= "{$tempData}\n";
            } else {
                if($isParentClose) {
                    //$data .= "</ul>\n{$indent}</li>\
                    $data .= "\n";
                } else {
                    $data .= "{$tempData}\n";
                }
            }
        }
        return "{$data}";
    }

    private function getRecursiveColumnKeyRawQuery($item, $depth = 0, $isParent = false, $isParentClose = false) {
        $data = "";
        if($item instanceof ColumnKeyModel) {
            $tempColumnKey  = new ColumnKey();
            $tempData = "INSERT INTO tbl_column_key"
                . "({$tempColumnKey->id}, {$tempColumnKey->working_table}, {$tempColumnKey->main_column}, {$tempColumnKey->key_type}, {$tempColumnKey->reference_column}, {$tempColumnKey->key_name}, {$tempColumnKey->unique_name}, {$tempColumnKey->modified_date}, {$tempColumnKey->created_date})"
                . " VALUES"
                . "('{$item->id}', '{$item->workingTable}', '{$item->mainColumn}', '{$item->keyType}', '{$item->referenceColumn}', '{$item->keyName}', '{$item->uniqueName}', '{$item->uniqueName}', '{$item->modifiedDate}', '{$item->createdDate}');";
            if($isParent) {
                $data .= "{$tempData}\n";
            } else {
                if($isParentClose) {
                    //$data .= "</ul>\n{$indent}</li>\
                    //$data .= "\n";
                } else {
                    $data .= "{$tempData}\n";
                }
            }
        }
        return "{$data}";
    }

    private function getRecursiveCompositeKeyRawQuery($item, $depth = 0, $isParent = false, $isParentClose = false) {
        $data = "";
        if($item instanceof CompositeKeyModel) {
            $tempCompositeKey = new CompositeKey();
            $tempData = "INSERT INTO tbl_composite_key"
                . "({$tempCompositeKey->key_id}, {$tempCompositeKey->id}, {$tempCompositeKey->primary_column}, {$tempCompositeKey->composite_column}, {$tempCompositeKey->key_name}, {$tempCompositeKey->modified_date}, {$tempCompositeKey->created_date})"
                . " VALUES"
                . "('{$item->keyId}', '{$item->id}', '{$item->primaryColumn}', '{$item->compositeColumn}', '{$item->keyName}', '{$item->modifiedDate}', '{$item->createdDate}');";
            if($isParent) {
                $data .= "{$tempData}\n";
            } else {
                if($isParentClose) {
                    //$data .= "</ul>\n{$indent}</li>\
                    //$data .= "\n";
                } else {
                    $data .= "{$tempData}\n";
                }
            }
        }
        return "{$data}";
    }
}
?>
