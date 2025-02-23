<?php
namespace RzSDK\Code\Module;
?>
<?php
use RzSDK\String\Utils\TextCase;
?>
<?php
class TextCaseSelectBox {
    public static function getSelectBox($selectedCase) {
        $selecBox = "<select name=\"text_case\">";
        foreach(TextCase::cases() as $case) {
            $name = $case->name;
            $value = $case->value;
            if($selectedCase == $value) {
                $selecBox .= "<option value=\"{$value}\" selected=\"selected\">{$value}</option>";
            } else {
                $selecBox .= "<option value=\"{$value}\">{$value}</option>";
            }
        }
        $selecBox .= "</select>";
        return $selecBox;
    }
}
?>