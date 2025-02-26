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
use App\DatabaseSchema\Helper\Database\Data\Retrieve\DbRetrieveDatabaseSchemaData;
use RzSDK\Log\DebugLog;

?>
<?php
class BuildDatabaseSchemaRawQuery {
    public function buildDatabaseSchemaRawQuery($schemaId = "", $tableId = "", $notTableId = "") {
        $schemaRawQuery = new DatabaseSchemaRawQuery();
        $rawQueryOutput = $schemaRawQuery->getDatabaseSchema($schemaId, $tableId, $notTableId, function ($item, $depth, $isParent, $isParentClose = false) {
            $data = "";
            //DebugLog::log($item);
            /*if($item instanceof DatabaseSchemaModel) {
                $data .= $this->getDatabaseSchemaRawQuery($item, $depth, $isParent, $isParentClose);
            } else if($item instanceof TableDataModel) {
                $data .= $this->getTableDataRawQuery($item, $depth, $isParent, $isParentClose);
            }*/
            $data .= $this->getDatabaseSchemaRawQuery($item, $depth, $isParent, $isParentClose);
            $data .= $this->getTableDataRawQuery($item, $depth, $isParent, $isParentClose);
            $data .= $this->getColumnDataRawQuery($item, $depth, $isParent, $isParentClose);
            $data .= $this->getColumnKeyRawQuery($item, $depth, $isParent, $isParentClose);
            $data .= $this->getCompositeKeyRawQuery($item, $depth, $isParent, $isParentClose);
            return $data;
        });
        return $rawQueryOutput;
    }

    private function getDatabaseSchemaRawQuery($item, $depth = 0, $isParent = false, $isParentClose = false) {
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

    private function getTableDataRawQuery($item, $depth = 0, $isParent = false, $isParentClose = false) {
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

    private function getColumnDataRawQuery($item, $depth = 0, $isParent = false, $isParentClose = false) {
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

    private function getColumnKeyRawQuery($item, $depth = 0, $isParent = false, $isParentClose = false) {
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

    private function getCompositeKeyRawQuery($item, $depth = 0, $isParent = false, $isParentClose = false) {
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
