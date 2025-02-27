<?php
namespace App\DatabaseSchema\Data\Mappers;
?>
<?php
use App\DatabaseSchema\Data\Entities\ColumnData;
use App\DatabaseSchema\Domain\Models\ColumnDataModel;
use RzSDK\Log\DebugLog;
use RzSDK\Log\LogType;
?>
<?php
class ColumnDataMapper {

    public static function getDataVarList(ColumnData $modelData) {
        return $modelData->getVarList();
    }

    public static function getDomainVarList(ColumnDataModel $modelData) {
        return $modelData->getVarList();
    }

    public static function toData(ColumnData $columnData): array {
        // Model data to array data
        $dataVarList = self::getDataVarList($columnData);
        $domainVarList = self::getDomainVarList(new ColumnDataModel());
        $dataList = array();
        for($i = 0; $i < count($dataVarList); $i++) {
            $dataList[$dataVarList[$i]] = $columnData->{$dataVarList[$i]};
        }
        return $dataList;
    }

    public static function toDomain(ColumnDataModel $columnData): array {
        // Model data to array data
        //DebugLog::log($tableData);
        $dataVarList = self::getDataVarList(new ColumnData());
        $domainVarList = self::getDomainVarList($columnData);
        //DebugLog::log($dataVarList);
        //DebugLog::log($domainVarList);
        $dataList = array();
        for($i = 0; $i < count($dataVarList); $i++) {
            $dataList[$dataVarList[$i]] = $columnData->{$domainVarList[$i]};
        }
        //DebugLog::log($dataList);
        return $dataList;
    }

    public static function toDataParams(ColumnData $columnData): array {
        $params = [];
        $data = self::toData($columnData);
        foreach($data as $key => $value) {
            $params[":$key"] = $value;
        }
        return $params;
    }

    public static function toDomainParams(ColumnDataModel $columnData): array {
        $params = [];
        $data = self::toDomain($columnData);
        foreach($data as $key => $value) {
            $params[":$key"] = $value;
        }
        return $params;
    }

    public static function toEntity($dbSchema): ColumnData {
        // Database array or object data to "Data" data
        $model = new ColumnData();
        /*$dataVarList = self::getDataVarList($model);
        $domainVarList = self::getDomainVarList($dbSchema);
        for($i = 0; $i < count($dataVarList); $i++) {
            $model->{$dataVarList[$i]} = $dbSchema->{$domainVarList[$i]};
        }*/
        if(is_array($dbSchema)) {
            $dataVarList = self::getDataVarList($model);
            //$domainVarList = self::getDomainVarList($model);
            for($i = 0; $i < count($dataVarList); $i++) {
                $model->{$dataVarList[$i]} = $dbSchema[$dataVarList[$i]];
            }
        } else if(is_object($dbSchema)) {
            $dataVarList = self::getDataVarList($model);
            //$domainVarList = self::getDomainVarList($model);
            for ($i = 0; $i < count($dataVarList); $i++) {
                $model->{$dataVarList[$i]} = $dbSchema->{$dataVarList[$i]};
            }
        }
        return $model;
    }

    public static function toModel($columnData): ColumnDataModel {
        $model = new ColumnDataModel();
        if(is_array($columnData)) {
            $dataVarList = self::getDataVarList(new ColumnData());
            $domainVarList = self::getDomainVarList($model);
            for($i = 0; $i < count($dataVarList); $i++) {
                $model->{$domainVarList[$i]} = $columnData[$dataVarList[$i]];
            }
        } else if(is_object($columnData)) {
            $dataVarList = self::getDataVarList($columnData);
            $domainVarList = self::getDomainVarList($model);
            for ($i = 0; $i < count($dataVarList); $i++) {
                $model->{$domainVarList[$i]} = $columnData->{$dataVarList[$i]};
            }
        }
        return $model;
    }
}
?>