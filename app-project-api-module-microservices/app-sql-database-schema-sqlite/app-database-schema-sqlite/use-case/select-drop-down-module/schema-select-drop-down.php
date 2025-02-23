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
    public static function schemaSelectDropDown($fieldName, array $schemaDataList, $selectedId = "") {
        //DebugLog::log($schemaDataList);
        $isSelected = false;
        $callbackSingleModelData = new RecursiveCallbackSingleModelData();
        $htmlOutput = "";
        //$htmlOutput = "<select name=\"$fieldName\" required=\"required\">";
        //$htmlOutput .= "<option value=\"\" selected=\"selected\">Select Database Schema Name</option>";
        $htmlOutput .= $callbackSingleModelData->onRecursionTraverse($schemaDataList, function($item, $level) use($selectedId) {
            if($item instanceof DatabaseSchemaModel) {
                if($selectedId == $item->id) {
                    $isSelected = true;
                    return "<option value=\"{$item->id}\" selected=\"selected\">{$item->schemaName}</option>";
                } else {
                    return "<option value=\"{$item->id}\">{$item->schemaName}</option>";
                }
            }
            return "";
        });
        $htmlOutput .= "</select>";
        if(!$isSelected) {
            $htmlOutput = "<select name=\"$fieldName\" required=\"required\"><option value=\"\" selected=\"selected\">Select Database Schema Name</option>$htmlOutput";
        } else {
            $htmlOutput = "<select name=\"$fieldName\" required=\"required\"><option value=\"\">Select Database Schema Name</option>$htmlOutput";
        }
        return $htmlOutput;
    }
}
?>