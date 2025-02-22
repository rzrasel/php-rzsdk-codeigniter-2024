<?php
use App\DatabaseSchema\Data\Repositories\ColumnDataRepositoryImpl;
use App\DatabaseSchema\Presentation\ViewModels\ColumnDataViewModel;
use App\DatabaseSchema\Presentation\Views\ColumnDataView;
use App\DatabaseSchema\Html\Select\DropDown\HtmlSelectDropDown;
use App\DatabaseSchema\Html\Select\DropDown\DataTypeSelectDropDown;
use App\DatabaseSchema\Helper\Database\Data\Retrieve\DbRetrieveDatabaseSchemaData;
use App\DatabaseSchema\Usages\Recursion\Callback\UsagesCallbackSingleModelData;
use RzSDK\Log\DebugLog;
?>
<?php
/*$repository = new ColumnDataRepositoryImpl();
$viewModel = new ColumnDataViewModel($repository);
$view = new ColumnDataView($viewModel);*/
?>
<?php
global $schemaDataList;
//$schemaDataList = $view->getAllTableDataGroupBySchema();
?>
<?php
$dbRetrieveDatabaseSchemaData = new DbRetrieveDatabaseSchemaData();
global $schemaDataList;
$schemaDataList = $dbRetrieveDatabaseSchemaData->getAllDatabaseSchemaData();
?>
<?php
$selectedTableName = "";
$selectedTableId = "";
$selectedColumnId = "";
$columnId = "";
if(!empty($_REQUEST)){
    if(!empty($_REQUEST["search_by_table_id"])) {
        $selectedTableId = $_REQUEST["search_by_table_id"];
    }
    if(!empty($_REQUEST["selected_table_name"])) {
        $selectedTableName = $_REQUEST["selected_table_name"];
    }
    if(!empty($_REQUEST["action_column_id"])) {
        $actionColumnId = $_REQUEST["action_column_id"];
        $columnId = $_REQUEST["action_column_id"];
    }
}
?>
<?php
$columnDataList = $dbRetrieveDatabaseSchemaData->getAllColumnDataByTableId($selectedTableId, $columnId);
DebugLog::log($columnDataList);
?>
<?php
$columnOrder = 1;
$selectedTableId = "";
if(!empty($_POST)) {
    //DebugLog::log($_POST);
    $selectedTableId = $_POST["table_id"];
    $columnOrder = $_POST["column_order"] + 1;
}
?>
<?php
/*$callbackSingleModelData = new UsagesCallbackSingleModelData();
$tableDataSelectDropDown = $callbackSingleModelData->getTableSelectDropDown("table_id", $schemaDataList);*/
//$tableSelectDropDown = HtmlSelectDropDown::tableSelectDropDown("table_id", $schemaDataList, $selectedTableId);
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
    //$view->createFromPostData($_POST);
}
?>
<?php
$selectedTableName = "";
$selectedTableId = "";
$selectedColumnId = "";
$columnId = "";
if(!empty($_REQUEST)){
    if(!empty($_REQUEST["search_by_table_id"])) {
        $selectedTableId = $_REQUEST["search_by_table_id"];
    }
    if(!empty($_REQUEST["selected_table_name"])) {
        $selectedTableName = $_REQUEST["selected_table_name"];
    }
    if(!empty($_REQUEST["action_column_id"])) {
        $actionColumnId = $_REQUEST["action_column_id"];
        $columnId = $_REQUEST["action_column_id"];
    }
}
?>
<form action="<?= $_SERVER["PHP_SELF"]; ?>" method="POST">
    <table class="data-entry-fields">
        <tr>
            <td></td>
            <td width="10px"></td>
            <td></td>
        </tr>
        <tr>
            <td>Table Name:</td>
            <td></td>
            <td>
                <input type="hidden" name="search_by_table_id" id="search_by_table_id" value="<?= $selectedTableId; ?>">
                <input type="text" name="selected_table_name" id="selected_table_name" required="required" placeholder="Selected Table name" value="<?= $selectedTableName; ?>" readonly="readonly" />
                <input type="hidden" name="table_id" id="table_id" value="<?= $selectedTableId; ?>">
            </td>
        </tr>
        <tr>
            <td>Column Name:</td>
            <td></td>
            <td>
                <input type="hidden" name="action_column_id" id="action_column_id" value="<?= $actionColumnId; ?>">
                <input type="hidden" name="id" id="id" value="<?= $columnId; ?>">
                <input type="text" name="column_name" id="column_name" required="required" placeholder="Column name" />
            </td>
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
            <td class="form-action-button"><button type="submit" name="button_action" value="delete" class="button-action-delete">Delete</button>&nbsp;<button type="submit" name="button_action" value="edit" class="button-action-edit">Edit</button></td>
        </tr>
    </table>
</form>