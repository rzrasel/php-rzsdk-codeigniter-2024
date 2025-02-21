<?php
namespace App\DatabaseSchema\Html\Select\DropDown;
?>
<?php
use App\DatabaseSchema\Helper\Recursion\Traverse\RecursiveCallbackSingleModelData;
use App\DatabaseSchema\Domain\Models\DatabaseSchemaModel;
use App\DatabaseSchema\Domain\Models\TableDataModel;
use App\DatabaseSchema\Domain\Models\ColumnKeyModel;
use RzSDK\Log\DebugLog;
?>
<?php
trait ColumnKeySelectDropDown {

    public static function columnKeySelectDropDown($fieldName, array $schemaDataList, $isRequired = true) {
        //DebugLog::log($schemaDataList);
        $required = "";
        if($isRequired) {
            $required = "required=\"required\"";
        }
        $callbackSingleModelData = new RecursiveCallbackSingleModelData();
        $htmlOutput = "<select name=\"$fieldName\" $required>";
        $htmlOutput .= "<option value=\"\" selected=\"selected\">Select Column Key</option>";
        $htmlOutput .= $callbackSingleModelData->traverseDatabaseSchema($schemaDataList, function($item, $extras = null) {
            //DebugLog::log($item);
            if($item instanceof DatabaseSchemaModel) {
                return "<optgroup label=\"{$item->schemaName}\">";
            } else if($item instanceof TableDataModel) {
                //return "<option value=\"{$item->id}\">{$item->tableName}</option>";
                return "<optgroup label=\"{$item->tableName}\">";
            } else if($item instanceof ColumnKeyModel) {
                $extraValue = "{$item->uniqueName}";
                if(!empty($extras)) {
                    $extraValue = "($extras) ↦ {$item->uniqueName}";
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