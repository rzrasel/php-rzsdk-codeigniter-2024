<?php
use App\DatabaseSchema\Data\Repositories\ExtractDatabaseSchemaImpl;
use App\DatabaseSchema\Presentation\ViewModels\ExtractDatabaseSchemaViewModel;
use App\DatabaseSchema\Presentation\Views\ExtractDatabaseSchemaView;
use App\DatabaseSchema\Html\Select\DropDown\HtmlSelectDropDown;
use App\DatabaseSchema\Schema\Build\Entity\ExtractSqlStatementToDataEntity;
use RzSDK\Log\DebugLog;
?>
<?php
?>
<?php
$repository = new ExtractDatabaseSchemaImpl();
$viewModel = new ExtractDatabaseSchemaViewModel($repository);
$view = new ExtractDatabaseSchemaView($viewModel);
$schemaDataList = $view->getAllSchemaData();
?>
<?php
$selectedSchemaId = "";
$schemaSelectDropDown = "";
$sqlStatement = "";
//$sqlToDataEntity = array();
if(!empty($_POST)) {
    $selectedSchemaId = $_POST["schema_id"];
    $sqlStatement = $_POST["sql_statement"];
    $view->onExtractSchema($_POST);
}
//DebugLog::log($sqlToDataEntity);
?>
<?php
$schemaSelectDropDown = HtmlSelectDropDown::schemaSelectDropDown("schema_id", $schemaDataList, $selectedSchemaId);
?>
<table class="form-heading">
    <tr>
        <td></td>
    </tr>
    <tr>
        <td>Extract Query To Database Schema</td>
    </tr>
    <tr>
        <td></td>
    </tr>
</table>
<form action="<?= $_SERVER["PHP_SELF"]; ?>" method="POST">
    <table class="data-entry-fields" width="100%">
        <tr>
            <td colspan="3" class="td-data-entry-message">
                <!--<div class="data-entry-message">
                    <div class="success">Success message</div>
                    <div class="error">Error message</div>
                     <div class="info">Info message</div>
                     <div class="default">Default message</div>
                </div>-->
                <div class="data-entry-message">
                    <div class="success">Success message</div>
                </div>
            </td>
        </tr>
        <tr>
            <td>Database Name:</td>
            <td></td>
            <td><?= $schemaSelectDropDown; ?></td>
        </tr>
        <tr>
            <td>SQL Statement:</td>
            <td></td>
            <td><textarea id="sql_statement" name="sql_statement" required="required"><?= $sqlStatement; ?></textarea></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td class="form-summit-button"><button type="submit">Extract SQL Statement</button></td>
        </tr>
    </table>
</form>