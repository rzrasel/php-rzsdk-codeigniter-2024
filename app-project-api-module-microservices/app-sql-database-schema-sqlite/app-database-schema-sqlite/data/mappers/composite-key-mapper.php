<?php
namespace App\DatabaseSchema\Data\Mappers;
?>
<?php
use App\DatabaseSchema\Data\Entities\CompositeKey;
use App\DatabaseSchema\Domain\Models\CompositeKeyModel;
use RzSDK\Log\DebugLog;
use RzSDK\Log\LogType;

class CompositeKeyMapper {

    public static function getDataVarList(CompositeKey $modelData) {
        return $modelData->getVarList();
    }

    public static function getDomainVarList(CompositeKeyModel $modelData) {
        return $modelData->getVarList();
    }

    public static function toData(CompositeKey $compositeKey): array {
        // Model data to array data
        $dataVarList = self::getDataVarList($compositeKey);
        $domainVarList = self::getDomainVarList(new CompositeKeyModel());
        $dataList = array();
        for($i = 0; $i < count($dataVarList); $i++) {
            $dataList[$dataVarList[$i]] = $compositeKey->{$dataVarList[$i]};
        }
        return $dataList;
    }

    public static function toDomain(CompositeKeyModel $compositeKey): array {
        // Model data to array data
        //DebugLog::log($tableData);
        $dataVarList = self::getDataVarList(new CompositeKey());
        $domainVarList = self::getDomainVarList($compositeKey);
        //DebugLog::log($dataVarList);
        //DebugLog::log($domainVarList);
        $dataList = array();
        for($i = 0; $i < count($dataVarList); $i++) {
            $dataList[$dataVarList[$i]] = $compositeKey->{$domainVarList[$i]};
        }
        //DebugLog::log($dataList);
        return $dataList;
    }

    public static function toDataParams(CompositeKey $compositeKey): array {
        $params = [];
        $data = self::toData($compositeKey);
        foreach($data as $key => $value) {
            $params[":$key"] = $value;
        }
        return $params;
    }

    public static function toDomainParams(CompositeKeyModel $compositeKey): array {
        $params = [];
        $data = self::toDomain($compositeKey);
        foreach($data as $key => $value) {
            $params[":$key"] = $value;
        }
        return $params;
    }

    public function toEntity($compositeKey): CompositeKey {
        // Database array or object data to "Data" data
        $model = new CompositeKey();
        $dataVarList = self::getDataVarList($model);
        $domainVarList = self::getDomainVarList($compositeKey);
        for($i = 0; $i < count($dataVarList); $i++) {
            $model->{$dataVarList[$i]} = $compositeKey->{$dataVarList[$i]};
        }
        return $model;
    }

    public function toModel($compositeKey): CompositeKeyModel {
        $model = new CompositeKeyModel();
        if(is_array($compositeKey)) {
            $dataVarList = self::getDataVarList(new CompositeKey());
            $domainVarList = self::getDomainVarList($model);
            for($i = 0; $i < count($dataVarList); $i++) {
                $model->{$domainVarList[$i]} = $compositeKey[$dataVarList[$i]];
            }
        } else if(is_object($compositeKey)) {
            $dataVarList = self::getDataVarList($compositeKey);
            $domainVarList = self::getDomainVarList($model);
            for ($i = 0; $i < count($dataVarList); $i++) {
                $model->{$domainVarList[$i]} = $compositeKey->{$dataVarList[$i]};
            }
        }
        return $model;
    }
}
?>