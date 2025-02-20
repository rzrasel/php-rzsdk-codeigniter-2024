<?php
namespace App\DatabaseSchema\Html\Select\DropDown;
?>
<?php
use App\DatabaseSchema\Helper\Recursion\Traverse\RecursiveCallbackSingleModelData;
use App\DatabaseSchema\Domain\Models\DatabaseSchemaModel;
use App\DatabaseSchema\Domain\Models\TableDataModel;
use App\DatabaseSchema\Domain\Models\ColumnDataModel;
use RzSDK\Log\DebugLog;
?>
<?php
trait ColumnSelectDropDown {

    public static function columnSelectDropDown($fieldName, array $schemaDataList, $isRequired = true) {
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
                return "<optgroup label=\"Database ↦ {$item->schemaName}\">";
            } else if($item instanceof TableDataModel) {
                //return "<option value=\"{$item->id}\">{$item->tableName}</option>";
                return "<optgroup label=\"Table ↦ {$item->tableName}\">";
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