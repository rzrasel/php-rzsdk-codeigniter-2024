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
$mainColumnSelectDropDown = HtmlSelectDropDown::columnKeySelectDropDown("main_column", $schemaDataList);
$referenceColumnSelectDropDown = HtmlSelectDropDown::columnSelectDropDown("reference_column", $schemaDataList, false);
$relationalKeyTypeSelectDropDown = HtmlSelectDropDown::relationalKeyTypeSelectDropDown("key_type");
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