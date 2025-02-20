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
use App\DatabaseSchema\Html\Select\DropDown\HtmlSelectDropDown;
use App\DatabaseSchema\Usages\Recursion\Callback\UsagesCallbackSingleModelData;
use RzSDK\Log\DebugLog;
?>
<?php
$repository = new ColumnKeyRepositoryImpl();
$viewModel = new ColumnKeyViewModel($repository);
$view = new ColumnKeyView($viewModel);
$schemaDataList = $view->getAllTableDataGroupByTable();
$callbackSingleModelData = new UsagesCallbackSingleModelData();
/*$mainColumnSelectDropDown = $callbackSingleModelData->getColumnSelectDropDown("main_column", $schemaDataList);
$referenceColumnSelectDropDown = $callbackSingleModelData->getColumnSelectDropDown("reference_column", $schemaDataList, false);*/
$mainColumnSelectDropDown = HtmlSelectDropDown::columnSelectDropDown("main_column", $schemaDataList);
$referenceColumnSelectDropDown = HtmlSelectDropDown::columnSelectDropDown("reference_column", $schemaDataList, false);
$relationalKeyTypeSelectDropDown = HtmlSelectDropDown::relationalKeyTypeSelectDropDown("key_type");
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
        <td>Column Key Entry</td>
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
            <td>Main Column Name:</td>
            <td></td>
            <td><?= $mainColumnSelectDropDown; ?></td>
        </tr>
        <tr>
            <td>Reference Column Name:</td>
            <td></td>
            <td><?= $referenceColumnSelectDropDown; ?></td>
        </tr>
        <tr>
            <td>Key Type:</td>
            <td></td>
            <td><?= $relationalKeyTypeSelectDropDown; ?></td>
        </tr>
        <tr>
            <td>Key Name:</td>
            <td></td>
            <td><input type="text" name="key_name" id="key_name" placeholder="Key name" /></td>
        </tr>
        <tr>
            <td>Unique Name:</td>
            <td></td>
            <td><input type="text" name="unique_name" id="unique_name" placeholder="Unique name" /></td>
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