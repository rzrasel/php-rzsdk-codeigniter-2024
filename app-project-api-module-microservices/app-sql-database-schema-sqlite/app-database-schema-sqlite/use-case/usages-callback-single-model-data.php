<?php
namespace App\DatabaseSchema\Usages\Recursion\Callback;
?>
<?php
use App\DatabaseSchema\Helper\Recursion\Traverse\RecursiveCallbackSingleModelData;
use App\DatabaseSchema\Domain\Models\DatabaseSchemaModel;
?>
<?php
class UsagesCallbackSingleModelData {
    public function getSchemaSelectDropDown(array $schemaDataList) {
        $callbackSingleModelData = new RecursiveCallbackSingleModelData();
        $htmlOutput = "<select name=\"schema_id\">";
        $htmlOutput .= $callbackSingleModelData->traverseDatabaseSchema($schemaDataList, function ($item) {
            if ($item instanceof DatabaseSchemaModel) {
                return "<option value=\"{$item->id}\">{$item->schemaName}</option>";
            }
            return "";
        });
        $htmlOutput .= "</select>";
        return $htmlOutput;
    }
}
?>
