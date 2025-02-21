<?php
namespace App\DatabaseSchema\Html\Select\DropDown;
?>
<?php
use App\DatabaseSchema\Helper\Recursion\Traverse\RecursiveCallbackSingleModelData;
use App\DatabaseSchema\Domain\Models\DatabaseSchemaModel;
use RzSDK\Log\DebugLog;
?>
<?php
trait IsDefaultSelectDropDown {
    public static function isDefaultSelectDropDown($fieldName, $selectedId = "") {
        //DebugLog::log($schemaDataList);
        $isSelected = false;
        $dataList = array(
            "" => "Select Is Default",
            "true" => "Yes Default",
            "false" => "No Default",
        );
        $htmlOutput = "<select name=\"$fieldName\" required=\"required\">";
        foreach($dataList as $key => $value) {
            if($selectedId == $key) {
                $htmlOutput .= "<option value=\"$key\" selected=\"selected\">$value</option>";
            } else {
                $htmlOutput .= "<option value=\"$key\">$value</option>";
            }
        }
        $htmlOutput .= "</select>";
        return $htmlOutput;
    }
}
?>