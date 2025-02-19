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
use App\DatabaseSchema\Data\Repositories\TableDataRepositoryImpl;
use App\DatabaseSchema\Presentation\ViewModels\TableDataViewModel;
use App\DatabaseSchema\Presentation\Views\TableDataView;
use App\DatabaseSchema\Usages\Recursion\Callback\UsagesCallbackSingleModelData;
use RzSDK\Log\DebugLog;
?>
<?php
$repository = new TableDataRepositoryImpl();
$viewModel = new TableDataViewModel($repository);
$view = new TableDataView($viewModel);
$schemaDataList = $view->getAllSchemaData();
$callbackSingleModelData = new UsagesCallbackSingleModelData();
$schemaSelectDropDown = $callbackSingleModelData->getSchemaSelectDropDown($schemaDataList);
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
        <td>Database Name:</td>
        <td></td>
        <td><?= $schemaSelectDropDown; ?></td>
    </tr>
    <tr>
        <td>Table Name:</td>
        <td></td>
        <td><input type="text" name="table_name" id="table_name" required="required" placeholder="Table name" /></td>
    </tr>
    <tr>
        <td>Column Prefix:</td>
        <td></td>
        <td><input type="text" name="column_prefix" id="column_prefix" placeholder="Column prefix" /></td>
    </tr>
    <tr>
        <td>Table Comment:</td>
        <td></td>
        <td><input type="text" name="table_comment" id="table_comment" placeholder="Table comment" /></td>
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