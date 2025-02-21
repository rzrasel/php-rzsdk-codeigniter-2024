<?php
namespace App\DatabaseSchema\Html\Select\DropDown;
?>
<?php
use App\DatabaseSchema\Helper\Data\Type\ColumnDataType;
use RzSDK\Log\DebugLog;
?>
<?php
trait DataTypeSelectDropDown {
    public static function dataTypeSelectDropDown($fieldName) {
        $htmlOutput = "<select name=\"$fieldName\" required=\"required\">";
        $htmlOutput .= "<option value=\"\" selected=\"selected\">Select Data Type</option>";
        $htmlOutput .= ColumnDataType::onRecursionTraverse(function(ColumnDataType $key) {
            return "<option value=\"{$key->name}\">{$key->name}</option>";
        });
        $htmlOutput .= "</select>";
        return $htmlOutput;
    }
}
?>