<?php
namespace App\DatabaseSchema\Html\Select\DropDown;
?>
<?php
use App\DatabaseSchema\Helper\Recursion\Traverse\RecursiveCallbackSingleModelData;
use App\DatabaseSchema\Domain\Models\DatabaseSchemaModel;
use RzSDK\Log\DebugLog;
?>
<?php
trait SchemaSelectDropDown {
    public static function schemaSelectDropDown($fieldName, array $schemaDataList) {
        //DebugLog::log($schemaDataList);
        $callbackSingleModelData = new RecursiveCallbackSingleModelData();
        $htmlOutput = "<select name=\"$fieldName\" required=\"required\">";
        $htmlOutput .= "<option value=\"\" selected=\"selected\">Select Database Schema Name</option>";
        $htmlOutput .= $callbackSingleModelData->onRecursionTraverse($schemaDataList, function ($item, $level) {
            if($item instanceof DatabaseSchemaModel) {
                return "<option value=\"{$item->id}\">{$item->schemaName}</option>";
            }
            return "";
        });
        $htmlOutput .= "</select>";
        return $htmlOutput;
    }
}
?>