<?php
/*$workingDir = __DIR__;
$workingDirName = basename($workingDir);
defined("CONST_STARTING_PATH") or define("CONST_STARTING_PATH", $workingDir);
defined("CONST_WORKING_DIR_NAME") or define("CONST_WORKING_DIR_NAME", $workingDirName);*/
?>
<?php
// public/index.php
//require __DIR__ . '/../vendor/autoload.php';
require_once("include.php");
?>
<?php
use App\DatabaseSchema\Data\Repositories\ColumnDataRepositoryImpl;
use App\DatabaseSchema\Presentation\ViewModels\ColumnDataViewModel;
use App\DatabaseSchema\Presentation\Views\ColumnDataView;
use App\DatabaseSchema\Html\Select\DropDown\HtmlSelectDropDown;
use App\DatabaseSchema\Html\Select\DropDown\DataTypeSelectDropDown;
use App\DatabaseSchema\Usages\Recursion\Callback\UsagesCallbackSingleModelData;
use RzSDK\Log\DebugLog;
?>
<?php
$repository = new ColumnDataRepositoryImpl();
$viewModel = new ColumnDataViewModel($repository);
$view = new ColumnDataView($viewModel);
$schemaDataList = $view->getAllTableDataGroupBySchema();
?>
<?php
$columnOrder = 1;
$selectedTableId = "";
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
<table>
    <tr>
        <td></td>
    </tr>
    <tr>
        <td>Column Data Entry</td>
    </tr>
    <tr>
        <td></td>
    </tr>
</table>
<form action="<?= $_SERVER["PHP_SELF"]; ?>" method="POST">
    <table>
        <tr>
            <td></td>
            <td width="10px"></td>
            <td></td>
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
            <td><input type="hidden" name="is_nullable" id="is_nullable" value="false">
                <input type="checkbox" name="is_nullable" id="is_nullable" value="true"></td>
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
        <tr>
            <td height="20px"></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td><button type="submit">Submit</button></td>
        </tr>
    </table>
</form>