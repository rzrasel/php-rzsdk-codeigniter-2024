<?php
namespace App\DatabaseSchema\Presentation\ViewModels;
?>
<?php
use RzSDK\Identification\UniqueIntId;
use App\DatabaseSchema\Domain\Repositories\ExtractDatabaseSchemaInterface;
use App\DatabaseSchema\Schema\Build\Entity\ExtractSqlStatementToDataEntity;
use App\DatabaseSchema\Data\Entities\TableData;
use App\DatabaseSchema\Data\Entities\ColumnData;
use App\DatabaseSchema\Data\Entities\ColumnKey;
use App\DatabaseSchema\Domain\Models\TableDataModel;
use App\DatabaseSchema\Domain\Models\ColumnDataModel;
use App\DatabaseSchema\Domain\Models\ColumnKeyModel;
use App\DatabaseSchema\Data\Mappers\ExtractSchemaTableMapper;
use App\DatabaseSchema\Data\Mappers\ExtractSchemaColumnMapper;
use App\DatabaseSchema\Data\Mappers\ExtractSchemaColumnKeyMapper;
use RzSDK\Log\DebugLog;
?>
<?php
class ExtractDatabaseSchemaViewModel {
    private $repository;

    public function __construct(ExtractDatabaseSchemaInterface $repository) {
        $this->repository = $repository;
    }

    public function getAllDatabaseSchemaData(): array|bool {
        return $this->repository->getAllDatabaseSchemaData();
    }

    public function onExtractSchema($schemaData) {
        //DebugLog::log($schemaData);
        if(empty($schemaData)) {
            return;
        }
        //DebugLog::log($schemaData);
        $schemaId = $schemaData["schema_id"];
        $sqlStatement = $schemaData["sql_statement"];
        $sqlStatementToDataEntity = new ExtractSqlStatementToDataEntity();
        $sqlToDataEntity = $sqlStatementToDataEntity->toDataEntity($sqlStatement);
        //DebugLog::log(ExtractSchemaTableMapper::toEntity($sqlToDataEntity));
        //DebugLog::log(ExtractSchemaColumnMapper::toEntity($sqlToDataEntity));
        //DebugLog::log(ExtractSchemaColumnKeyMapper::toEntity($sqlToDataEntity));
        //DebugLog::log($sqlToDataEntity);
        $tableDataModel = $this->onInsertTableData($schemaId, $sqlToDataEntity);
        //DebugLog::log($tableDataModel);
        $this->onInsertColumnData($tableDataModel, $sqlToDataEntity);
        $this->onInsertColumnKeyData($schemaId, $tableDataModel, $sqlToDataEntity);
    }

    private function onInsertTableData($schemaId, $sqlToDataEntity) {
        //
        $uniqueIntId = new UniqueIntId();
        $tableData = ExtractSchemaTableMapper::toEntity($sqlToDataEntity);
        //DebugLog::log($tableData);
        $tempTableData = new TableData();
        $tempTableData->setVars();
        $tableDataModelModel = new TableDataModel();
        //
        $tableName = trim($tableData->table_name);
        $tableName = preg_replace("/\s+/", "_", $tableName);
        $tableName = preg_replace("/-/", "_", $tableName);
        //
        $tableDataModelModel->id = $uniqueIntId->getId();
        $tableDataModelModel->schemaId = $schemaId;
        $tableDataModelModel->tableOrder = 0;
        $tableDataModelModel->tableName = $tableName;
        $tableDataModelModel->columnPrefix = $tableData->column_prefix;
        $tableDataModelModel->tableComment = $tableData->table_comment;
        $tableDataModelModel->modifiedDate = date('Y-m-d H:i:s');
        $tableDataModelModel->createdDate = date('Y-m-d H:i:s');
        //
        //DebugLog::log($tableDataModelModel);
        $tableDataModelModel = $this->repository->onInsertTableData($tableDataModelModel);
        return $tableDataModelModel;
    }

    private function onInsertColumnData(TableDataModel $tableDataModel, $sqlToDataEntity) {
        $columnDataList = ExtractSchemaColumnMapper::toEntity($sqlToDataEntity);
        //DebugLog::log($columnDataList);
        $uniqueIntId = new UniqueIntId();
        $tempColumnData = new ColumnData();
        $tempColumnData->setVars();
        //DebugLog::log($columnDataList);
        foreach($columnDataList as $columnDataItem) {
            //DebugLog::log($columnDataItem);
            $columnDataItem->table_id = $tableDataModel->id;
            /*$tempTableData = new TableData();
            $tempTableData->setVars();*/
            //
            $columnName = trim($columnDataItem->{$tempColumnData->column_name});
            $columnName = preg_replace("/\s+/", "_", $columnName);
            $columnName = preg_replace("/-/", "_", $columnName);
            if(empty($columnName)) {
                continue;
            }
            //
            $dataType = trim($columnDataItem->{$tempColumnData->data_type});
            $isNullable = trim($columnDataItem->{$tempColumnData->is_nullable});
            $haveDefault = trim($columnDataItem->{$tempColumnData->have_default});
            $defaultValue = $columnDataItem->{$tempColumnData->default_value};
            $columnComment = $columnDataItem->{$tempColumnData->column_comment};
            //
            $columnDataModel = new ColumnDataModel();
            $columnDataModel->id = $uniqueIntId->getId();
            $columnDataModel->tableId = $tableDataModel->id;
            $columnDataModel->columnOrder = 0;
            $columnDataModel->columnName = $columnName;
            $columnDataModel->dataType = strtoupper($dataType);

            $columnDataModel->isNullable = $isNullable;
            $columnDataModel->haveDefault = $haveDefault;
            $columnDataModel->defaultValue = $defaultValue;
            $columnDataModel->columnComment = $columnComment;

            $columnDataModel->modifiedDate = date('Y-m-d H:i:s');
            $columnDataModel->createdDate = date('Y-m-d H:i:s');
            //DebugLog::log($columnDataModel);
            $columnDataModel = $this->repository->onInsertColumnData($columnDataModel);
            //return $columnDataModel;
        }
    }

    private function onInsertColumnKeyData($schemaId, TableDataModel $tableDataModel, $sqlToDataEntity) {
        $columnKeyList = ExtractSchemaColumnKeyMapper::toEntity($sqlToDataEntity);
        //DebugLog::log($columnKeyList);
        $uniqueIntId = new UniqueIntId();
        $tempColumnKey = new ColumnKey();
        $tempColumnKey->setVars();
        //DebugLog::log($columnKeyList);
        foreach($columnKeyList as $columnKeyItem) {
            //
            //DebugLog::log($columnKeyItem);
            $workingTableId = $columnKeyItem->working_table;
            $primaryColumnId = $columnKeyItem->main_column;
            $keyType = "PRIMARY";
            if($columnKeyItem->key_type == "primary key") {
                $keyType = "PRIMARY";
            } else if($columnKeyItem->key_type == "foreign key") {
                $keyType = "FOREIGN";
            } else if($columnKeyItem->key_type == "unique key") {
                $keyType = "UNIQUE";
            }
            $referenceColumn = $columnKeyItem->reference_column;
            $uniqueName = substr(strtolower($keyType), 0, 1) . "k>";
            $uniqueName .= "{$workingTableId}>{$primaryColumnId}";
            if(!empty($referenceColumn)) {
                $uniqueName .= ">{$referenceColumn[0]}>{$referenceColumn[1]}";
            }
            //
            $columnKeyModel = new ColumnKeyModel();
            $columnKeyModel->id = $uniqueIntId->getId();
            $columnKeyModel->workingTable = $tableDataModel->id;
            $columnKeyModel->mainColumn = $primaryColumnId;

            $columnKeyModel->keyType = $keyType;
            $columnKeyModel->referenceColumn = $referenceColumn;
            $columnKeyModel->keyName = null;
            $columnKeyModel->uniqueName = $uniqueName;

            $columnKeyModel->modifiedDate = date('Y-m-d H:i:s');
            $columnKeyModel->createdDate = date('Y-m-d H:i:s');
            //DebugLog::log($columnKeyModel);
            $columnKeyModel = $this->repository->onInsertColumnKey($schemaId, $columnKeyModel);
            //
        }
    }
}
?>
