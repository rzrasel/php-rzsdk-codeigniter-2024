<?php
namespace RzSDK\Code\Module;
?>
<?php
class NumOfTabSelectBox {
    public static function getSelectBox($selectedNumber, $totalNumber = 100) {
        $selecBox = "<select name=\"total_tabs\">";
        for($i = 0; $i <= $totalNumber; $i++) {
            if($i == $selectedNumber) {
                $selecBox .= "<option value=\"{$i}\" selected=\"selected\">{$i} Tabs</option>";
            } else {
                $selecBox .= "<option value=\"{$i}\">{$i} Tabs</option>";
            }
        }
        $selecBox .= "</select>";
        return $selecBox;
    }
}
?>