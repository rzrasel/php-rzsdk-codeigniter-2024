<?php
use App\DatabaseSchema\Data\Repositories\DeleteDatabaseSchemaQueryRepositoryImpl;
use App\DatabaseSchema\Presentation\ViewModels\DeleteDatabaseSchemaQueryViewModel;
use App\DatabaseSchema\Presentation\Views\DeleteDatabaseSchemaQueryView;
use RzSDK\Log\DebugLog;
?>
<?php
$repository = new DeleteDatabaseSchemaQueryRepositoryImpl();
$viewModel = new DeleteDatabaseSchemaQueryViewModel($repository);
$view = new DeleteDatabaseSchemaQueryView($viewModel);
?>
<?php
if(!empty($_POST)) {
    //DebugLog::log($_POST);
    $view->onDeleteDatabaseSchemaQuery($_POST);
}
?>
<table class="form-heading">
    <tr>
        <td></td>
    </tr>
    <tr>
        <td>Delete Database Schema</td>
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
            <td>Delete Composite Key:</td>
            <td><input type="hidden" value="button-action-delete-post"/></td>
            <td class="form-summit-action-button"><button type="submit" name="database_delete_action_key" value="delete_composite_key" class="button-action-delete-composite-key">Delete Composite Key</button></td>
        </tr>
        <tr>
            <td>Delete Column Key:</td>
            <td></td>
            <td class="form-summit-action-button"><button type="submit" name="database_delete_action_key" value="delete_column_key" class="button-action-delete-column-key">Delete Column Key</button></td>
        </tr>
        <tr>
            <td>Delete Column Data:</td>
            <td></td>
            <td class="form-summit-action-button"><button type="submit" name="database_delete_action_key" value="delete_column_data" class="button-action-delete-column-data">Delete Column Data</button></td>
        </tr>
        <tr>
            <td>Delete Table Data:</td>
            <td></td>
            <td class="form-summit-action-button"><button type="submit" name="database_delete_action_key" value="delete_table_data" class="button-action-delete-table-data">Delete Table Data</button></td>
        </tr>
        <tr>
            <td>Delete Database Schema Data:</td>
            <td></td>
            <td class="form-summit-action-button"><button type="submit" name="database_delete_action_key" value="delete_database_schema_data" class="button-action-delete-database-schema-data">Delete Database Schema Data</button></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
        </tr>
    </table>
</form>