<?php
namespace App\DatabaseSchema\Data\Mappers;
?>
<?php
use App\DatabaseSchema\Data\Entities\ColumnKey;
use App\DatabaseSchema\Domain\Models\ColumnKeyModel;
use RzSDK\Log\DebugLog;
use RzSDK\Log\LogType;
?>
<?php
class ExtractSchemaColumnKeyMapper {
    public static function getDataVarList(ColumnKey $modelData) {
        return $modelData->getVarList();
    }

    public static function getDomainVarList(ColumnKeyModel $modelData) {
        return $modelData->getVarList();
    }

    public static function toEntity($extractedDataList): array {
        // Database array or object data to "Data" data
        //$model = new ColumnData();
        $dataList = array();
        if(empty($extractedDataList)) {
            return $dataList;
        }
        if(empty($extractedDataList["constraint"])) {
            return $dataList;
        }
        $model = new ColumnKey();
        $model->setVars();
        $dataVarList = self::getDataVarList($model);
        //$domainVarList = self::getDomainVarList($extractedDataList);
        foreach($extractedDataList["constraint"] as $constraintDataItem) {
            //DebugLog::log($constraintDataItem);
            $model = new ColumnKey();
            for($i = 0; $i < count($dataVarList); $i++) {
                $key = $dataVarList[$i];
                if(key_exists($key, $constraintDataItem)) {
                    $value = $constraintDataItem[$key];
                    if(empty($value)) {
                        $value = null;
                    }
                    if(!is_array($value)) {
                        $value = !empty($constraintDataItem[$key]) ? trim($constraintDataItem[$key]) : null;
                    }
                    if($key == $model->working_table) {
                        $value = trim($value);
                        $value = ltrim($value, "tbl");
                        $value = ltrim($value, "_");
                    }
                    if($key == $model->reference_column) {
                        if(!empty($value)) {
                            $value[0] = trim($value[0]);
                            $value[0] = ltrim($value[0], "tbl");
                            $value[0] = ltrim($value[0], "_");
                        }
                    }
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