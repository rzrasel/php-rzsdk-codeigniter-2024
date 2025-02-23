<?php
namespace RzSDK\Code\Module;
?>
<?php
use RzSDK\Padding\Utils\PaddingPlace;
?>
<?php
class PaddingPlaceSelectBox {
    public static function getSelectBox($selectedPlace) {
        $selecBox = "<select name=\"padding_place\">";
        foreach(PaddingPlace::cases() as $case) {
            $name = $case->name;
            $value = $case->value;
            if($selectedPlace == $value) {
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