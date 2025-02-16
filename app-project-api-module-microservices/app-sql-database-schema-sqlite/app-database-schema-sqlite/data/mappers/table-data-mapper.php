<?php
namespace App\DatabaseSchema\Data\Mappers;
?>
<?php
use App\DatabaseSchema\Data\Entities\TableData;
use App\DatabaseSchema\Domain\Models\TableDataModel;

class TableDataMapper {

    public static function getDataVarList(TableData $modelData) {
        return $modelData->getVarList();
    }

    public static function getDomainVarList(TableDataModel $modelData) {
        return $modelData->getVarList();
    }

    public static function toData(TableData $tableData): array {
        // Model data to array data
        $dataVarList = self::getDataVarList($tableData);
        $domainVarList = self::getDomainVarList(new TableDataModel());
        $dataList = array();
        for($i = 0; $i < count($dataVarList); $i++) {
            $dataList[$dataVarList[$i]] = $tableData->{$dataVarList[$i]};
        }
        return $dataList;
        /*return [
            'schema_id' => $tableData->schema_id,
            'id' => $tableData->id,
            'table_name' => $tableData->table_name,
            'column_prefix' => $tableData->column_prefix,
            'table_comment' => $tableData->table_comment,
            'modified_date' => $tableData->modified_date,
            'created_date' => $tableData->created_date,
        ];*/
    }

    public static function toDomain(TableDataModel $tableData): array {
        // Model data to array data
        $dataVarList = self::getDataVarList(new TableData());
        $domainVarList = self::getDomainVarList($tableData);
        $dataList = array();
        for($i = 0; $i < count($dataVarList); $i++) {
            $dataList[$dataVarList[$i]] = $tableData->{$domainVarList[$i]};
        }
        return $dataList;
        /*return [
            'schema_id' => $tableData->schemaId,
            'id' => $tableData->id,
            'table_name' => $tableData->tableName,
            'column_prefix' => $tableData->columnPrefix,
            'table_comment' => $tableData->tableComment,
            'modified_date' => $tableData->modifiedDate,
            'created_date' => $tableData->createdDate,
        ];*/
    }

    public static function toDataParams(TableData $tableData): array {
        $params = [];
        $data = self::toData($tableData);
        foreach($data as $key => $value) {
            $params[":$key"] = $value;
        }
        return $params;
    }

    public static function toDomainParams(TableDataModel $tableData): array {
        $params = [];
        $data = self::toDomain($tableData);
        foreach($data as $key => $value) {
            $params[":$key"] = $value;
        }
        return $params;
    }

    public function toEntity($tableData): TableData {
        // Database array or object data to "Data" data
        $model = new TableData();
        $dataVarList = self::getDataVarList($model);
        $domainVarList = self::getDomainVarList($tableData);
        for($i = 0; $i < count($dataVarList); $i++) {
            $model->{$dataVarList[$i]} = $tableData->{$dataVarList[$i]};
        }
        /*$model = new TableData();
        $model->schema_id = $tableData->schema_id;
        $model->id = $tableData->id;
        $model->table_name = $tableData->table_name;
        $model->column_prefix = $tableData->column_prefix;
        $model->table_comment = $tableData->table_comment;
        $model->modified_date = $tableData->modified_date;
        $model->created_date = $tableData->created_date;*/
        return $model;
    }

    public function toModel($tableData): TableDataModel {
        $model = new TableDataModel();
        $dataVarList = self::getDataVarList(new TableData());
        $domainVarList = self::getDomainVarList($tableData);
        for($i = 0; $i < count($dataVarList); $i++) {
            $model->{$domainVarList[$i]} = $tableData->{$dataVarList[$i]};
        }
        /*$model->schemaId = $tableData->schema_id;
        $model->id = $tableData->id;
        $model->tableName = $tableData->table_name;
        $model->columnPrefix = $tableData->column_prefix;
        $model->tableComment = $tableData->table_comment;
        $model->modifiedDate = $tableData->modified_date;
        $model->createdDate = $tableData->created_date;*/
        return $model;
    }
}
?>