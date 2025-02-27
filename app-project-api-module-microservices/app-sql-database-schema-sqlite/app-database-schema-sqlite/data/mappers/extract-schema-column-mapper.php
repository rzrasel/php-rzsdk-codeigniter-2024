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
class ExtractSchemaColumnMapper {
    public static function getDataVarList(ColumnData $modelData) {
        return $modelData->getVarList();
    }

    public static function getDomainVarList(ColumnDataModel $modelData) {
        return $modelData->getVarList();
    }

    public static function toEntity($extractedDataList): array {
        // Database array or object data to "Data" data
        //$model = new ColumnData();
        $dataList = array();
        if(empty($extractedDataList)) {
            return $dataList;
        }
        if(empty($extractedDataList["column"])) {
            return $dataList;
        }
        $model = new ColumnData();
        $model->setVars();
        $dataVarList = self::getDataVarList($model);
        //$domainVarList = self::getDomainVarList($extractedDataList);
        foreach($extractedDataList["column"] as $columnDataItem) {
            //DebugLog::log($columnDataItem);
            $model = new ColumnData();
            for($i = 0; $i < count($dataVarList); $i++) {
                $key = $dataVarList[$i];
                if(key_exists($key, $columnDataItem)) {
                    $value = !empty($columnDataItem[$key]) ? trim($columnDataItem[$key]) : null;
                    $model->{$dataVarList[$i]} = $value;
                } else {
                    $model->{$dataVarList[$i]} = null;
                }
            }
            $dataList[] = $model;
        }
        return $dataList;
    }
}
?>