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
    public static function tableSelectDropDown($fieldName, array $schemaDataList, $selectedTableId = "") {
        $isSelected = false;
        //DebugLog::log($schemaDataList);
        $callbackSingleModelData = new RecursiveCallbackSingleModelData();
        $htmlOutput = "";
        //$htmlOutput = "<select name=\"$fieldName\" required=\"required\">";
        //$htmlOutput .= "<option value=\"\" selected=\"selected\">Select Table Name</option>";
        $htmlOutput .= $callbackSingleModelData->traverseDatabaseSchema($schemaDataList, function ($item) use ($selectedTableId) {
            //DebugLog::log($item);
            if($item instanceof DatabaseSchemaModel) {
                return "<optgroup label=\"Database ↦ {$item->schemaName}\">";
            } else if($item instanceof TableDataModel) {
                $isSelected = true;
                if($selectedTableId == $item->id) {
                    return "<option value=\"{$item->id}\" selected=\"selected\">{$item->tableName}</option>";
                } else {
                    return "<option value=\"{$item->id}\">{$item->tableName}</option>";
                }
            }
            return "";
        });
        $htmlOutput .= "</optgroup></select>";
        if(!$isSelected) {
            $htmlOutput = "<select name=\"$fieldName\" required=\"required\"><option value=\"\" selected=\"selected\">Select Database Schema Name</option>$htmlOutput";
        } else {
            $htmlOutput = "<select name=\"$fieldName\" required=\"required\"><option value=\"\">Select Database Schema Name</option>$htmlOutput";
        }
        return $htmlOutput;
    }
}
?>