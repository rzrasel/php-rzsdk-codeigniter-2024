<?php
require_once("include.php");
?>
<?php
use App\DatabaseSchema\Data\Repositories\ColumnDataRepositoryImpl;
use App\DatabaseSchema\Presentation\ViewModels\ColumnDataViewModel;
use App\DatabaseSchema\Presentation\Views\ColumnDataView;
use App\DatabaseSchema\Html\Select\DropDown\HtmlSelectDropDown;
use App\DatabaseSchema\Helper\Database\Data\Retrieve\DbRetrieveDatabaseSchemaData;
use App\DatabaseSchema\Html\Select\DropDown\DataTypeSelectDropDown;
use App\DatabaseSchema\Usages\Recursion\Callback\UsagesCallbackSingleModelData;
use RzSDK\Log\DebugLog;
?>
<?php
$dbRetrieveDatabaseSchemaData = new DbRetrieveDatabaseSchemaData();
?>
<?php
$repository = new ColumnDataRepositoryImpl();
$viewModel = new ColumnDataViewModel($repository);
$view = new ColumnDataView($viewModel);
?>
<?php
$selectedSchemaId = "";
$selectedTableId = "";
$columnOrder = 1;
?>
<?php
if(!empty($_REQUEST)) {
    //DebugLog::log($_REQUEST);
    $selectedSchemaId = $_REQUEST["search_by_schema_id"];
}
if (!empty($_REQUEST)) {
    if (!empty($_REQUEST["search_by_table_id"])) {
        $selectedTableId = $_REQUEST["search_by_table_id"];
    }
}
?>
<?php
global $schemaDataList;
//$schemaDataList = $view->getAllTableDataGroupBySchema();
$schemaDataList = $dbRetrieveDatabaseSchemaData->getAllDatabaseSchemaData($selectedSchemaId);
?>
<?php
if(!empty($_POST)) {
    $selectedTableId = $_POST["table_id"];
    $columnOrder = $_POST["column_order"] + 1;
}
?>
<?php
/*$callbackSingleModelData = new UsagesCallbackSingleModelData();
$tableDataSelectDropDown = $callbackSingleModelData->getTableSelectDropDown("table_id", $schemaDataList);*/
$tableSelectDropDown = HtmlSelectDropDown::tableSelectDropDown("table_id", $schemaDataList, $selectedTableId);
$dataTypeSelectDropDown = HtmlSelectDropDown::dataTypeSelectDropDown("data_type");
$isNullSelectDropDown = HtmlSelectDropDown::isNullSelectDropDown("is_nullable");
$isDefaultSelectDropDown = HtmlSelectDropDown::isDefaultSelectDropDown("have_default");
//$jsonData = json_encode($schemaDataList);
//$schemaDataList = json_decode($jsonData, true);
//DebugLog::log($schemaDataList);
?>
<?php
if(!empty($_POST)) {
    foreach($_POST as $key => $value) {
        $_POST[$key] = htmlspecialchars($value);
        $_POST[$key] = trim($value);
        if(empty($_POST[$key])) {
            $_POST[$key] = NULL;
        }
    }
    //DebugLog::log($_POST);
    $view->createFromPostData($_POST);
}
?>
<form action="<?= $_SERVER["PHP_SELF"]; ?>" method="POST">
    <table class="data-entry-fields">
        <tr>
            <td colspan="3" class="td-data-entry-message">
                <!--<div class="data-entry-message">
                    <div class="success">Success message</div>
                    <div class="error">Error message</div>
                     <div class="info">Info message</div>
                     <div class="default">Default message</div>
                </div>-->
                <input type="hidden" name="search_by_schema_id" id="search_by_schema_id" value="<?= $selectedSchemaId; ?>">
                <input type="hidden" name="search_by_table_id" id="search_by_table_id" value="<?= $selectedTableId; ?>">
            </td>
        </tr>
        <tr>
            <td>Table Name:</td>
            <td></td>
            <td><?= $tableSelectDropDown; ?></td>
        </tr>
        <tr>
            <td>Column Name:</td>
            <td></td>
            <td><input type="text" name="column_name" id="column_name" required="required" placeholder="Column name" /></td>
        </tr>
        <tr>
            <td>Column Order:</td>
            <td></td>
            <td><input type="number" name="column_order" id="column_order" required="required" placeholder="Column order" value="<?= $columnOrder; ?>" min="0" max="5000" /></td>
        </tr>
        <tr>
            <td>Data Type:</td>
            <td></td>
            <td><?= $dataTypeSelectDropDown; ?></td>
        </tr>
        <tr>
            <td>Data Length:</td>
            <td></td>
            <td><input type="number" name="data_length" id="data_length" required="required" placeholder="Data length" value="0" min="0" max="5000" /></td>
        </tr>
        <tr>
            <td>Is Null:</td>
            <td></td>
            <td><!--<input type="hidden" name="is_nullable" id="is_nullable" value="false">
                <input type="checkbox" name="is_nullable" id="is_nullable" value="true">--><?= $isNullSelectDropDown; ?></td>
        </tr>
        <tr>
            <td>Have Default:</td>
            <td></td>
            <td><!--<input type="hidden" name="is_default" id="is_default" value="false">
                <input type="checkbox" name="is_default" id="is_default" value="true">--><?= $isDefaultSelectDropDown; ?></td>
        </tr>
        <tr>
            <td>Default Value:</td>
            <td></td>
            <td><input type="text" name="default_value" id="default_value" placeholder="Default value" /></td>
        </tr>
        <tr>
            <td>Column Comment:</td>
            <td></td>
            <td><input type="text" name="column_comment" id="column_comment" placeholder="Column comment" /></td>
        </tr>
        <!--<tr>
            <td height="20px"></td>
            <td></td>
            <td></td>
        </tr>-->
        <tr>
            <td></td>
            <td></td>
            <td class="form-summit-button"><button type="submit">Submit</button></td>
        </tr>
    </table>
</form>