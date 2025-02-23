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
    public static function tableSelectDropDown($fieldName, array $schemaDataList, $selectedTableId = "", $isRequired = true) {
        $isSelected = false;
        $requiredHtml = $isRequired ? 'required="required"' : '';

        $callbackSingleModelData = new RecursiveCallbackSingleModelData();
        $htmlOptions = "";

        // Traverse the schema and build the options
        $htmlOptions .= $callbackSingleModelData->traverseDatabaseSchema($schemaDataList, function($item) use($selectedTableId, &$isSelected) {
            if ($item instanceof DatabaseSchemaModel) {
                return "<optgroup label=\"Database ↦ {$item->schemaName}\">";
            } else if ($item instanceof TableDataModel) {
                $selected = ($selectedTableId == $item->id) ? 'selected="selected"' : '';
                if ($selected) $isSelected = true; // Mark that a table is selected
                return "<option value=\"{$item->id}\" {$selected}>{$item->tableName}</option>";
            } else {
                return ""; // Ignore unknown items
            }
        });

        // Close last optgroup properly if any items exist
        if (!empty($htmlOptions)) {
            $htmlOptions .= "</optgroup>";
        }

        // Construct final select box with or without a pre-selected value
        $defaultOption = $isSelected
            ? '<option value="">Select Database Table Name</option>'
            : '<option value="" selected="selected">Select Database Table Name</option>';

        return "<select name=\"$fieldName\" {$requiredHtml}>{$defaultOption}{$htmlOptions}</select>";
    }
    public static function tableSelectDropDownV1($fieldName, array $schemaDataList, $selectedTableId = "", $isRequired = true) {
        $isSelected = false;
        $requiredHtml = "";
        if($isRequired) {
            $requiredHtml = "required=\"required\"";
        }
        //DebugLog::log($schemaDataList);
        $callbackSingleModelData = new RecursiveCallbackSingleModelData();
        $htmlOutput = "";
        //$htmlOutput = "<select name=\"$fieldName\" required=\"required\">";
        //$htmlOutput .= "<option value=\"\" selected=\"selected\">Select Table Name</option>";
        $htmlOutput .= $callbackSingleModelData->traverseDatabaseSchema($schemaDataList, function($item) use($selectedTableId) {
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
            $htmlOutput = "<select name=\"$fieldName\" {$requiredHtml}><option value=\"\" selected=\"selected\">Select Database Table Name</option>$htmlOutput";
        } else {
            $htmlOutput = "<select name=\"$fieldName\" {$requiredHtml}><option value=\"\">Select Database Table Name</option>$htmlOutput";
        }
        return $htmlOutput;
    }
}
?>