<?php
namespace App\DatabaseSchema\Schema\Unique\Name;
?>
<?php
use App\DatabaseSchema\Helper\Recursion\Traverse\RecursiveCallbackSingleModelData;
use App\DatabaseSchema\Domain\Models\DatabaseSchemaModel;
use App\DatabaseSchema\Domain\Models\TableDataModel;
use App\DatabaseSchema\Domain\Models\ColumnDataModel;
use RzSDK\Log\DebugLog;
?>
<?php
trait ColumnNameUniqueText {
    public static function columnNameUniqueText($fieldName = "", $schemaDataList) {
        $callbackSingleModelData = new RecursiveCallbackSingleModelData();
        $htmlOutput = "";
        $htmlOutput .= $callbackSingleModelData->traverseDatabaseSchema($schemaDataList, function ($item, $extras = null) {
            //DebugLog::log($item);
            if($item instanceof DatabaseSchemaModel) {
                return "{$item->schemaName}→";
            } else if($item instanceof TableDataModel) {
                return "{$item->tableName}→";
            } else if($item instanceof ColumnDataModel) {
                $extraValue = "{$item->columnName}";
                if(!empty($extras)) {
                    //$extraValue = "{$item->columnName} ↦ ($extras)";
                }
                return "{$item->columnName}→";
            }
            return "";
        });
        return $htmlOutput;
    }
}
?>