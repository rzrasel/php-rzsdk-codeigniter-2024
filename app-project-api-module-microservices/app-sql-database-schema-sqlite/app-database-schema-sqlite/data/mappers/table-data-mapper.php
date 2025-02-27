<?php
namespace App\DatabaseSchema\Data\Mappers;
?>
<?php
use App\DatabaseSchema\Data\Entities\TableData;
use App\DatabaseSchema\Domain\Models\TableDataModel;
use RzSDK\Log\DebugLog;
use RzSDK\Log\LogType;
?>
<?php
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

    public static function toEntity($dbSchema): TableData {
        // Database array or object data to "Data" data
        //DebugLog::log($dbSchema);
        $model = new TableData();
        if(is_array($dbSchema)) {
            $dataVarList = self::getDataVarList($model);
            //$domainVarList = self::getDomainVarList($tableData);
            for($i = 0; $i < count($dataVarList); $i++) {
                $model->{$dataVarList[$i]} = $dbSchema[$dataVarList[$i]];
            }
        } else if(is_object($dbSchema)) {
            $dataVarList = self::getDataVarList($model);
            //$domainVarList = self::getDomainVarList($tableData);
            for($i = 0; $i < count($dataVarList); $i++) {
                //$model->{$dataVarList[$i]} = $dbSchema->{$dataVarList[$i]};
                $model->{$dataVarList[$i]} = $dbSchema->{$dataVarList[$i]};
            }
        }
        //DebugLog::log($model);
        return $model;
    }

    public static function toModel($tableData): TableDataModel {
        $model = new TableDataModel();
        if(is_array($tableData)) {
            $dataVarList = self::getDataVarList(new TableData());
            $domainVarList = self::getDomainVarList($model);
            for($i = 0; $i < count($dataVarList); $i++) {
                $model->{$domainVarList[$i]} = $tableData[$dataVarList[$i]];
            }
        } else if(is_object($tableData)) {
            $dataVarList = self::getDataVarList($tableData);
            $domainVarList = self::getDomainVarList($model);
            for ($i = 0; $i < count($dataVarList); $i++) {
                $model->{$domainVarList[$i]} = $tableData->{$dataVarList[$i]};
            }
        }
        return $model;
    }
}
?>