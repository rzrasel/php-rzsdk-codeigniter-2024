<?php
require_once("include.php");
?>
<?php
use App\DatabaseSchema\Data\Repositories\CompositeKeyRepositoryImpl;
use App\DatabaseSchema\Presentation\ViewModels\CompositeKeyViewModel;
use App\DatabaseSchema\Presentation\Views\CompositeKeyView;
use App\DatabaseSchema\Html\Select\DropDown\HtmlSelectDropDown;
use App\DatabaseSchema\Helper\Database\Data\Retrieve\DbRetrieveDatabaseSchemaData;
use RzSDK\Log\DebugLog;
?>
<?php
$repository = new CompositeKeyRepositoryImpl();
$viewModel = new CompositeKeyViewModel($repository);
$view = new CompositeKeyView($viewModel);
?>
<?php
$dbRetrieveDatabaseSchemaData = new DbRetrieveDatabaseSchemaData();
?>
<?php
$selectedSchemaId = "";
$selectedTableId = "";
?>
<?php
if (!empty($_REQUEST)) {
    if (!empty($_REQUEST["search_by_schema_id"])) {
        $selectedSchemaId = $_REQUEST["search_by_schema_id"];
    }
    if (!empty($_REQUEST["search_by_table_id"])) {
        $selectedTableId = $_REQUEST["search_by_table_id"];
    }
}
?>
<?php
//$schemaDataList = $view->getAllTableDataGroupByTable();
$schemaDataList = $dbRetrieveDatabaseSchemaData->getAllDatabaseSchemaData($selectedSchemaId, $selectedTableId);
//DebugLog::log($schemaDataList);
//
$columnKeySelectDropDown = HtmlSelectDropDown::columnKeySelectDropDown("key_id", $schemaDataList);
$primaryColumnSelectDropDown = HtmlSelectDropDown::columnSelectDropDown("primary_column", $schemaDataList, false);
$schemaDataList = $dbRetrieveDatabaseSchemaData->getAllDatabaseSchemaData($selectedSchemaId, "", "");
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
<form action="<?= $_SERVER["PHP_SELF"]; ?>" method="POST">
    <table class="data-entry-fields">
        <tr>
            <td></td>
            <td width="10px">
                <input type="hidden" name="search_by_schema_id" id="search_by_schema_id" value="<?= $selectedSchemaId; ?>">
                <input type="hidden" name="search_by_table_id" id="search_by_table_id" value="<?= $selectedTableId; ?>">
            </td>
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