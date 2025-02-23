<?php
namespace RzSDK\Code\Module;
?>
<?php
class NumOfPaddingSelectBox {
    public static function getSelectBox($selectedNumber, $totalNumber = 250) {
        $selecBox = "<select name=\"total_characters\">";
        for($i = 1; $i <= $totalNumber; $i++) {
            if($i == $selectedNumber) {
                $selecBox .= "<option value=\"{$i}\" selected=\"selected\">{$i} Characters</option>";
            } else {
                $selecBox .= "<option value=\"{$i}\">{$i} Characters</option>";
            }
        }
        $selecBox .= "</select>";
        return $selecBox;
    }
}
?>