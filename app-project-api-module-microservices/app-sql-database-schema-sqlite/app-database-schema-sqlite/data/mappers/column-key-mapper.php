<?php
namespace App\DatabaseSchema\Data\Mappers;
?>
<?php
use App\DatabaseSchema\Data\Entities\ColumnKey;
use App\DatabaseSchema\Domain\Models\ColumnKeyModel;
use RzSDK\Log\DebugLog;
use RzSDK\Log\LogType;

class ColumnKeyMapper {

    public static function getDataVarList(ColumnKey $modelData) {
        return $modelData->getVarList();
    }

    public static function getDomainVarList(ColumnKeyModel $modelData) {
        return $modelData->getVarList();
    }

    public static function toData(ColumnKey $columnKey): array {
        // Model data to array data
        $dataVarList = self::getDataVarList($columnKey);
        $domainVarList = self::getDomainVarList(new ColumnKeyModel());
        $dataList = array();
        for($i = 0; $i < count($dataVarList); $i++) {
            $dataList[$dataVarList[$i]] = $columnKey->{$dataVarList[$i]};
        }
        return $dataList;
    }

    public static function toDomain(ColumnKeyModel $columnKey): array {
        // Model data to array data
        //DebugLog::log($tableData);
        $dataVarList = self::getDataVarList(new ColumnKey());
        $domainVarList = self::getDomainVarList($columnKey);
        //DebugLog::log($dataVarList);
        //DebugLog::log($domainVarList);
        $dataList = array();
        for($i = 0; $i < count($dataVarList); $i++) {
            $dataList[$dataVarList[$i]] = $columnKey->{$domainVarList[$i]};
        }
        //DebugLog::log($dataList);
        return $dataList;
    }

    public static function toDataParams(ColumnKey $columnKey): array {
        $params = [];
        $data = self::toData($columnKey);
        foreach($data as $key => $value) {
            $params[":$key"] = $value;
        }
        return $params;
    }

    public static function toDomainParams(ColumnKeyModel $columnKey): array {
        $params = [];
        $data = self::toDomain($columnKey);
        foreach($data as $key => $value) {
            $params[":$key"] = $value;
        }
        return $params;
    }

    public function toEntity($columnKey): ColumnKey {
        // Database array or object data to "Data" data
        $model = new ColumnKey();
        $dataVarList = self::getDataVarList($model);
        $domainVarList = self::getDomainVarList($columnKey);
        for($i = 0; $i < count($dataVarList); $i++) {
            $model->{$dataVarList[$i]} = $columnKey->{$dataVarList[$i]};
        }
        return $model;
    }

    public static function toModel($columnKey): ColumnKeyModel {
        $model = new ColumnKeyModel();
        if(is_array($columnKey)) {
            $dataVarList = self::getDataVarList(new ColumnKey());
            $domainVarList = self::getDomainVarList($model);
            for($i = 0; $i < count($dataVarList); $i++) {
                $model->{$domainVarList[$i]} = $columnKey[$dataVarList[$i]];
            }
        } else if(is_object($columnKey)) {
            $dataVarList = self::getDataVarList($columnKey);
            $domainVarList = self::getDomainVarList($model);
            for ($i = 0; $i < count($dataVarList); $i++) {
                $model->{$domainVarList[$i]} = $columnKey->{$dataVarList[$i]};
            }
        }
        return $model;
    }
}
?>