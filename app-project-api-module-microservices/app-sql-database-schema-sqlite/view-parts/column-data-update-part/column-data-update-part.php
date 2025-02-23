<?php
use App\DatabaseSchema\Helper\Database\Data\Retrieve\DbRetrieveDatabaseSchemaData;
use App\DatabaseSchema\Data\Repositories\ColumnDataRepositoryImpl;
use App\DatabaseSchema\Presentation\ViewModels\ColumnDataViewModel;
use App\DatabaseSchema\Presentation\Views\ColumnDataView;
use RzSDK\Log\DebugLog;
?>
<?php
$repository = new ColumnDataRepositoryImpl();
$viewModel = new ColumnDataViewModel($repository);
$view = new ColumnDataView($viewModel);
?>
<?php
if(!empty($_POST)) {
    //DebugLog::log($_POST);
    foreach($_POST as $key => $value) {
        $_POST[$key] = htmlspecialchars($value);
        $_POST[$key] = trim($value);
        if(empty($_POST[$key])) {
            $_POST[$key] = NULL;
        }
    }
    //DebugLog::log($_POST);
    $view->updateFromPostData($_POST);
}
?>
<table class="form-heading">
    <tr>
        <td></td>
    </tr>
    <tr>
        <td>Column Data Update</td>
    </tr>
    <tr>
        <td></td>
    </tr>
</table>
<table>
    <tr>
        <td class="data-entry-container">
            <?php
            if(!empty($_REQUEST)){
                if(!empty($_REQUEST["search_by_table_id"]) && !empty($_REQUEST["action_column_id"])) {
                    require_once("column-data-update-action-part.php");
                }
            }
            ?>
        </td>
    </tr>
    <tr>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td class="data-search-by-container">
            <?php
            require_once("column-data-update-search-part.php");
            ?>
        </td>
    </tr>
    <tr>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td class="data-search-result-container">
            <?php
            if(!empty($_REQUEST)){
                if(!empty($_REQUEST["search_by_table_id"])) {
                    require_once("column-data-update-search-result-part.php");
                }
            }
            ?>
        </td>
    </tr>
</table>