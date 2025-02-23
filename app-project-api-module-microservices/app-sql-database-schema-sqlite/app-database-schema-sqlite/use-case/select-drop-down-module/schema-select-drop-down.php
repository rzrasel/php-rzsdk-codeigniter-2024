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
    public static function schemaSelectDropDown($fieldName, array $schemaDataList, $selectedId = "", $isRequired = true) {
        $isSelected = false;
        $requiredHtml = $isRequired ? 'required="required"' : '';

        $callbackSingleModelData = new RecursiveCallbackSingleModelData();
        $htmlOptions = "";

        // Traverse the schema list and generate options
        $htmlOptions .= $callbackSingleModelData->onRecursionTraverse($schemaDataList, function($item, $level) use($selectedId, &$isSelected) {
            if ($item instanceof DatabaseSchemaModel) {
                $selected = ($selectedId == $item->id) ? 'selected="selected"' : '';
                if ($selected) $isSelected = true; // Mark that a schema is selected
                return "<option value=\"{$item->id}\" {$selected}>{$item->schemaName}</option>";
            }
            return "";
        });

        // Default "Select" option handling
        $defaultOption = !$isSelected
            ? '<option value="" selected="selected">Select Database Schema Name</option>'
            : '<option value="">Select Database Schema Name</option>';

        // Final select dropdown construction
        return "<select name=\"$fieldName\" {$requiredHtml}>{$defaultOption}{$htmlOptions}</select>";
    }
    public static function schemaSelectDropDownV1($fieldName, array $schemaDataList, $selectedId = "", $isRequired = true) {
        //DebugLog::log($schemaDataList);
        $isSelected = false;
        $requiredHtml = "";
        if($isRequired) {
            $requiredHtml = "required=\"required\"";
        }
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
            $htmlOutput = "<select name=\"$fieldName\" {$requiredHtml}><option value=\"\" selected=\"selected\">Select Database Schema Name</option>$htmlOutput";
        } else {
            $htmlOutput = "<select name=\"$fieldName\" required=\"required\"><option value=\"\">Select Database Schema Name</option>$htmlOutput";
        }
        return $htmlOutput;
    }
}
?>