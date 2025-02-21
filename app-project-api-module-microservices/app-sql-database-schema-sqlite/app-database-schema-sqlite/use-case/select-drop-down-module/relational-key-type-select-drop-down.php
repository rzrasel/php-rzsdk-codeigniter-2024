<?php
namespace App\DatabaseSchema\Html\Select\DropDown;
?>
<?php
use App\DatabaseSchema\Helper\Key\Type\RelationalKeyType;
use RzSDK\Log\DebugLog;
?>
<?php
trait RelationalKeyTypeSelectDropDown {
public static function relationalKeyTypeSelectDropDown($fieldName) {
    $htmlOutput = "<select name=\"$fieldName\" required=\"required\">";
    $htmlOutput .= "<option value=\"\" selected=\"selected\">Select Data Type</option>";
    $htmlOutput .= RelationalKeyType::onRecursionTraverse(function(RelationalKeyType $key) {
        return "<option value=\"{$key->name}\">{$key->name}</option>";
    });
    $htmlOutput .= "</select>";
    return $htmlOutput;
}
}
?>