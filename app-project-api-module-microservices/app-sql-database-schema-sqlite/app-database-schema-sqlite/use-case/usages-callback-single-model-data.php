<?php
namespace App\DatabaseSchema\Usages\Recursion\Callback;
?>
<?php
use App\DatabaseSchema\Helper\Recursion\Traverse\RecursiveCallbackSingleModelData;
use App\DatabaseSchema\Domain\Models\DatabaseSchemaModel;
use App\DatabaseSchema\Domain\Models\TableDataModel;
use App\DatabaseSchema\Domain\Models\ColumnDataModel;
use RzSDK\Log\DebugLog;
?>
<?php
class UsagesCallbackSingleModelData {
    public function getSchemaSelectDropDown($fieldName, array $schemaDataList) {
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

    public function getTableSelectDropDown($fieldName, array $schemaDataList) {
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

    public function getColumnSelectDropDown($fieldName, array $schemaDataList, $isRequired = true) {
        //DebugLog::log($schemaDataList);
        $required = "";
        if($isRequired) {
            $required = "required=\"required\"";
        }
        $callbackSingleModelData = new RecursiveCallbackSingleModelData();
        $htmlOutput = "<select name=\"$fieldName\" $required>";
        $htmlOutput .= "<option value=\"\" selected=\"selected\">Select Column Name</option>";
        $htmlOutput .= $callbackSingleModelData->traverseDatabaseSchema($schemaDataList, function ($item, $extras = null) {
            //DebugLog::log($item);
            if($item instanceof DatabaseSchemaModel) {
                return "<optgroup label=\"{$item->schemaName}\">";
            } else if($item instanceof TableDataModel) {
                //return "<option value=\"{$item->id}\">{$item->tableName}</option>";
                return "<optgroup label=\"{$item->tableName}\">";
            } else if($item instanceof ColumnDataModel) {
                $extraValue = "{$item->columnName}";
                if(!empty($extras)) {
                    $extraValue = "{$item->columnName} ↦ ($extras)";
                }
                return "<option value=\"{$item->id}\">$extraValue</option>";
            }
            return "";
        });
        $htmlOutput .= "</optgroup></optgroup></select>";
        return $htmlOutput;
    }
}
?>
