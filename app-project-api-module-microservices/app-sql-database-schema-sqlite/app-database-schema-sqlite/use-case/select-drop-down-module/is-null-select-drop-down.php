<?php
namespace App\DatabaseSchema\Html\Select\DropDown;
?>
<?php
use App\DatabaseSchema\Helper\Recursion\Traverse\RecursiveCallbackSingleModelData;
use App\DatabaseSchema\Domain\Models\DatabaseSchemaModel;
use RzSDK\Log\DebugLog;
?>
<?php
trait IsNullSelectDropDown {
    public static function isNullSelectDropDown($fieldName, $selectedId = "") {
        //DebugLog::log($schemaDataList);
        $isSelected = false;
        $dataList = array(
            "" => "Select Is Null",
            "true" => "Yes Null",
            "false" => "Not Null",
        );
        $htmlOutput = "<select name=\"$fieldName\" required=\"required\">";
        foreach($dataList as $key => $value) {
            if(strtolower($selectedId) == $key) {
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