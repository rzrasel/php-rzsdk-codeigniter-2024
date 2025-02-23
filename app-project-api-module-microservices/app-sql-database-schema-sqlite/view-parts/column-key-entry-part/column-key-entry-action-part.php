<?php
use App\DatabaseSchema\Data\Repositories\ColumnKeyRepositoryImpl;
use App\DatabaseSchema\Presentation\ViewModels\ColumnKeyViewModel;
use App\DatabaseSchema\Presentation\Views\ColumnKeyView;
use App\DatabaseSchema\Html\Select\DropDown\HtmlSelectDropDown;
use App\DatabaseSchema\Helper\Database\Data\Retrieve\DbRetrieveDatabaseSchemaData;
use App\DatabaseSchema\Usages\Recursion\Callback\UsagesCallbackSingleModelData;
use App\DatabaseSchema\Helper\Data\Finder\DatabaseSchemaDataFinder;
use RzSDK\Log\DebugLog;
?>
<?php
$repository = new ColumnKeyRepositoryImpl();
$viewModel = new ColumnKeyViewModel($repository);
$view = new ColumnKeyView($viewModel);
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
//$callbackSingleModelData = new UsagesCallbackSingleModelData();
/*$mainColumnSelectDropDown = $callbackSingleModelData->getColumnSelectDropDown("main_column", $schemaDataList);
$referenceColumnSelectDropDown = $callbackSingleModelData->getColumnSelectDropDown("reference_column", $schemaDataList, false);*/
$mainColumnSelectDropDown = HtmlSelectDropDown::columnSelectDropDown("main_column", $schemaDataList);
$schemaDataList = $dbRetrieveDatabaseSchemaData->getAllDatabaseSchemaData($selectedSchemaId, "", "");
$referenceColumnSelectDropDown = HtmlSelectDropDown::columnSelectDropDown("reference_column", $schemaDataList);
$relationalKeyTypeSelectDropDown = HtmlSelectDropDown::relationalKeyTypeSelectDropDown("key_type");
//$jsonData = json_encode($schemaDataList);
//$schemaDataList = json_decode($jsonData, true);
//DebugLog::log($schemaDataList);
//$dataFinderList = (new DatabaseSchemaDataFinder($schemaDataList))->getColumnDetails("174003003822055028");
//DebugLog::log($dataFinderList);
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
            <td>Working Table Column Name:</td>
            <td></td>
            <td><?= $mainColumnSelectDropDown; ?></td>
        </tr>
        <tr>
            <td>Reference Table Column Name:</td>
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