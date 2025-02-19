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
use App\DatabaseSchema\Data\Repositories\ColumnKeyRepositoryImpl;
use App\DatabaseSchema\Presentation\ViewModels\ColumnKeyViewModel;
use App\DatabaseSchema\Presentation\Views\ColumnKeyView;
use App\DatabaseSchema\Usages\Recursion\Callback\UsagesCallbackSingleModelData;
use RzSDK\Log\DebugLog;
?>
<?php
$repository = new ColumnKeyRepositoryImpl();
$viewModel = new ColumnKeyViewModel($repository);
$view = new ColumnKeyView($viewModel);
$schemaDataList = $view->getAllTableDataGroupByTable();
$callbackSingleModelData = new UsagesCallbackSingleModelData();
$tableDataSelectDropDown = $callbackSingleModelData->getColumnSelectDropDown($schemaDataList);
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
    $view->createFromPostData($_POST);
}
?>
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
        <td><?= $tableDataSelectDropDown; ?></td>
    </tr>
    <tr>
        <td>Column Name:</td>
        <td></td>
        <td><input type="text" name="column_name" id="column_name" required="required" placeholder="Column name" /></td>
    </tr>
    <tr>
        <td>Data Type:</td>
        <td></td>
        <td><input type="text" name="data_type" id="data_type" required="required" placeholder="Data type" /></td>
    </tr>
    <tr>
        <td>Is Null:</td>
        <td></td>
        <td><input type="hidden" name="is_nullable" id="is_nullable" value="0">
            <input type="checkbox" name="is_nullable" id="is_nullable" value="1"></td>
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