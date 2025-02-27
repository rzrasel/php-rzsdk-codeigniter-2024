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
class ExtractSchemaTableMapper {
    public static function getDataVarList(TableData $modelData) {
        return $modelData->getVarList();
    }

    public static function getDomainVarList(TableDataModel $modelData) {
        return $modelData->getVarList();
    }

    public static function toEntity($extractedDataList): TableData {
        // Database array or object data to "Data" data
        $model = new TableData();
        if(empty($extractedDataList)) {
            return $model;
        }
        if(empty($extractedDataList["table"])) {
            return $model;
        }
        $dataVarList = self::getDataVarList($model);
        //$domainVarList = self::getDomainVarList($extractedDataList);
        for($i = 0; $i < count($dataVarList); $i++) {
            /*$key = $dataVarList[$i];
            if(key_exists($key, $extractedDataList)) {
                $value = trim($extractedDataList[$key]);
                $value = ltrim($value, "tbl_");
                $model->{$dataVarList[$i]} = $value;
            } else {
                $model->{$dataVarList[$i]} = null;
            }*/
            $model->{$dataVarList[$i]} = null;
        }
        $value = trim($extractedDataList["table"]);
        $value = ltrim($value, "tbl");
        $value = ltrim($value, "_");
        $model->table_name = $value;
        return $model;
    }
}
?>