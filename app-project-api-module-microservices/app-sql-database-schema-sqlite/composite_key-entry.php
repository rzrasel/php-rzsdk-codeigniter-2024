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
use App\DatabaseSchema\Data\Repositories\CompositeKeyRepositoryImpl;
use App\DatabaseSchema\Presentation\ViewModels\CompositeKeyViewModel;
use App\DatabaseSchema\Presentation\Views\CompositeKeyView;
use App\DatabaseSchema\Html\Select\DropDown\HtmlSelectDropDown;
use RzSDK\Log\DebugLog;
?>
<?php
$repository = new CompositeKeyRepositoryImpl();
$viewModel = new CompositeKeyViewModel($repository);
$view = new CompositeKeyView($viewModel);
?>
<?php
$schemaDataList = $view->getAllTableDataGroupByTable();
//DebugLog::log($schemaDataList);
//
$columnKeySelectDropDown = HtmlSelectDropDown::columnKeySelectDropDown("key_id", $schemaDataList);
$primaryColumnSelectDropDown = HtmlSelectDropDown::columnSelectDropDown("primary_column", $schemaDataList, false);
$compositeColumnSelectDropDown = HtmlSelectDropDown::columnSelectDropDown("composite_column", $schemaDataList, false);
$relationalKeyTypeSelectDropDown = HtmlSelectDropDown::relationalKeyTypeSelectDropDown("key_type");
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
    $view->createFromPostData($_POST, $schemaDataList);
}
?>
<table>
    <tr>
        <td></td>
    </tr>
    <tr>
        <td>Composite Key Entry</td>
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
            <td>Column Key:</td>
            <td></td>
            <td><?= $columnKeySelectDropDown; ?></td>
        </tr>
        <tr>
            <td>Primary Column:</td>
            <td></td>
            <td><?= $primaryColumnSelectDropDown; ?></td>
        </tr>
        <tr>
            <td>Composite Column:</td>
            <td></td>
            <td><?= $compositeColumnSelectDropDown; ?></td>
        </tr>
        <tr>
            <td>Key Name:</td>
            <td></td>
            <td><input type="text" name="key_name" id="key_name" placeholder="Key name" /></td>
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