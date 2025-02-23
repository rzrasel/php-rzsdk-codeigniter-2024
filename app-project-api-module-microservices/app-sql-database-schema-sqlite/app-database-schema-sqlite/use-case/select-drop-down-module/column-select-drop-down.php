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

    public static function columnSelectDropDown($fieldName, array $schemaDataList, $selectedId = "", $isRequired = true) {
        $isSelected = false;
        $requiredHtml = $isRequired ? 'required="required"' : '';

        $callbackSingleModelData = new RecursiveCallbackSingleModelData();
        $htmlOptions = "";

        // Traverse schema list and generate options
        $htmlOptions .= $callbackSingleModelData->traverseDatabaseSchema($schemaDataList, function($item, $extras = null) use ($selectedId, &$isSelected) {
            if ($item instanceof DatabaseSchemaModel) {
                return "<optgroup label=\"Database ↦ {$item->schemaName}\">";
            }
            if ($item instanceof TableDataModel) {
                return "<optgroup label=\"Table ↦ {$item->tableName}\">";
            }
            if ($item instanceof ColumnDataModel) {
                $extraValue = "{$item->columnName}";
                if (!empty($extras)) {
                    $extraValue .= " ↦ ($extras)";
                }

                $selected = ($selectedId == $item->id) ? 'selected="selected"' : '';
                if ($selected) $isSelected = true; // Mark that a column is selected

                return "<option value=\"{$item->id}\" {$selected}>{$extraValue}</option>";
            }
            return "";
        });

        // Close any open <optgroup> properly
        if (!empty($htmlOptions)) {
            $htmlOptions .= "</optgroup>";
        }

        // Default "Select" option handling
        $defaultOption = !$isSelected
            ? '<option value="" selected="selected">Select Column Name</option>'
            : '<option value="">Select Column Name</option>';

        // Construct the final <select> element
        return "<select name=\"$fieldName\" {$requiredHtml}>{$defaultOption}{$htmlOptions}</select>";
    }

    public static function columnSelectDropDownV1($fieldName, array $schemaDataList, $selectedId = "", $isRequired = true) {
        //DebugLog::log($schemaDataList);
        $isSelected = false;
        $required = "";
        if($isRequired) {
            $required = "required=\"required\"";
        }
        $callbackSingleModelData = new RecursiveCallbackSingleModelData();
        $htmlOutput = "<select name=\"$fieldName\" $required>";
        $htmlOutput .= "<option value=\"\" selected=\"selected\">Select Column Name</option>";
        $htmlOutput .= $callbackSingleModelData->traverseDatabaseSchema($schemaDataList, function($item, $extras = null) {
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