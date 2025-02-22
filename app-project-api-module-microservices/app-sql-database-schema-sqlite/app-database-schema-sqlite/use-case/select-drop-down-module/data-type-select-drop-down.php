<?php
namespace App\DatabaseSchema\Html\Select\DropDown;
?>
<?php
use App\DatabaseSchema\Helper\Data\Type\ColumnDataType;
use RzSDK\Log\DebugLog;
?>
<?php
trait DataTypeSelectDropDown {
    public static function dataTypeSelectDropDown($fieldName, $selectedDataType = "") {
        $isSelected = false;
        //$htmlOutput = "<select name=\"$fieldName\" required=\"required\">";
        //$htmlOutput .= "<option value=\"\" selected=\"selected\">Select Data Type</option>";
        $htmlOutput = "";
        $htmlOutput .= ColumnDataType::onRecursionTraverse(function(ColumnDataType $key) use($selectedDataType) {
            if(strtoupper($selectedDataType) == $key->name) {
                $isSelected = true;
                return "<option value=\"{$key->name}\" selected=\"selected\">{$key->name}</option>";
            } else {
                return "<option value=\"{$key->name}\">{$key->name}</option>";
            }
        });
        if($isSelected) {
            $tempHtmlOutput = "<select name=\"$fieldName\" required=\"required\">";
            $tempHtmlOutput .= "<option value=\"\">Select Data Type</option>";
            $htmlOutput = "{$tempHtmlOutput}{$htmlOutput}";
        } else {
            $tempHtmlOutput = "<select name=\"$fieldName\" required=\"required\">";
            $tempHtmlOutput .= "<option value=\"\" selected=\"selected\">Select Data Type</option>";
            $htmlOutput = "{$tempHtmlOutput}{$htmlOutput}";
        }
        $htmlOutput .= "</select>";
        return $htmlOutput;
    }
}
?>