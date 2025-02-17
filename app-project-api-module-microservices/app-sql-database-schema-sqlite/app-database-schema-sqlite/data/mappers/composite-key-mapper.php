<?php
namespace App\DatabaseSchema\Data\Mappers;
?>
<?php
use App\DatabaseSchema\Data\Entities\TableData;
use App\DatabaseSchema\Domain\Models\TableDataModel;
use RzSDK\Log\DebugLog;
use RzSDK\Log\LogType;

class CompositeKeyMapper {

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
    }

    public static function toDomain(TableDataModel $tableData): array {
        // Model data to array data
        //DebugLog::log($tableData);
        $dataVarList = self::getDataVarList(new TableData());
        $domainVarList = self::getDomainVarList($tableData);
        //DebugLog::log($dataVarList);
        //DebugLog::log($domainVarList);
        $dataList = array();
        for($i = 0; $i < count($dataVarList); $i++) {
            $dataList[$dataVarList[$i]] = $tableData->{$domainVarList[$i]};
        }
        //DebugLog::log($dataList);
        return $dataList;
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
        return $model;
    }

    public function toModel($tableData): TableDataModel {
        $model = new TableDataModel();
        $dataVarList = self::getDataVarList(new TableData());
        $domainVarList = self::getDomainVarList($tableData);
        for($i = 0; $i < count($dataVarList); $i++) {
            $model->{$domainVarList[$i]} = $tableData->{$dataVarList[$i]};
        }
        return $model;
    }
}
?>