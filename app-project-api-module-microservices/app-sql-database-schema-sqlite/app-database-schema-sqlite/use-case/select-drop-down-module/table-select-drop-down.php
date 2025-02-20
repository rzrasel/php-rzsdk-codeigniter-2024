<?php
namespace App\DatabaseSchema\Html\Select\DropDown;
?>
<?php
use App\DatabaseSchema\Helper\Recursion\Traverse\RecursiveCallbackSingleModelData;
use App\DatabaseSchema\Domain\Models\DatabaseSchemaModel;
use App\DatabaseSchema\Domain\Models\TableDataModel;
use RzSDK\Log\DebugLog;
?>
<?php
trait TableSelectDropDown {
    public static function tableSelectDropDown($fieldName, array $schemaDataList) {
        //DebugLog::log($schemaDataList);
        $callbackSingleModelData = new RecursiveCallbackSingleModelData();
        $htmlOutput = "<select name=\"$fieldName\" required=\"required\">";
        $htmlOutput .= "<option value=\"\" selected=\"selected\">Select Table Name</option>";
        $htmlOutput .= $callbackSingleModelData->traverseDatabaseSchema($schemaDataList, function ($item) {
            //DebugLog::log($item);
            if($item instanceof DatabaseSchemaModel) {
                return "<optgroup label=\"{$item->schemaName}\">";
            } else if($item instanceof TableDataModel) {
                return "<option value=\"{$item->id}\">{$item->tableName}</option>";
            }
            return "";
        });
        $htmlOutput .= "</optgroup></select>";
        return $htmlOutput;
    }
}
?>