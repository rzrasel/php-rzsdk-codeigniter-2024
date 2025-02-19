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
    public function getSchemaSelectDropDown(array $schemaDataList) {
        //DebugLog::log($schemaDataList);
        $callbackSingleModelData = new RecursiveCallbackSingleModelData();
        $htmlOutput = "<select name=\"schema_id\">";
        $htmlOutput .= $callbackSingleModelData->onRecursionTraverse($schemaDataList, function ($item, $level) {
            if($item instanceof DatabaseSchemaModel) {
                return "<option value=\"{$item->id}\">{$item->schemaName}</option>";
            }
            return "";
        });
        $htmlOutput .= "</select>";
        return $htmlOutput;
    }

    public function getTableSelectDropDown(array $schemaDataList) {
        //DebugLog::log($schemaDataList);
        $callbackSingleModelData = new RecursiveCallbackSingleModelData();
        $htmlOutput = "<select name=\"table_id\">";
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

    public function getColumnSelectDropDown(array $schemaDataList) {
        //DebugLog::log($schemaDataList);
        $callbackSingleModelData = new RecursiveCallbackSingleModelData();
        $htmlOutput = "<select name=\"table_id\">";
        $htmlOutput .= $callbackSingleModelData->traverseDatabaseSchema($schemaDataList, function ($item) {
            //DebugLog::log($item);
            if($item instanceof DatabaseSchemaModel) {
                return "<optgroup label=\"{$item->schemaName}\">";
            } else if($item instanceof TableDataModel) {
                //return "<option value=\"{$item->id}\">{$item->tableName}</option>";
                return "<optgroup label=\"{$item->tableName}\">";
            } else if($item instanceof ColumnDataModel) {
                return "<option value=\"{$item->id}\">{$item->columnName}</option>";
            }
            return "";
        });
        $htmlOutput .= "</optgroup></optgroup></select>";
        return $htmlOutput;
    }
}
?>
