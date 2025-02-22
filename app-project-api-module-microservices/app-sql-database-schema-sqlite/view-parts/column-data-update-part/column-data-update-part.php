<?php
use App\DatabaseSchema\Helper\Database\Data\Retrieve\DbRetrieveDatabaseSchemaData;
use RzSDK\Log\DebugLog;
?>
<?php
/*$dbRetrieveDatabaseSchemaData = new DbRetrieveDatabaseSchemaData();
global $schemaDataList;
$schemaDataList = $dbRetrieveDatabaseSchemaData->getAllDatabaseSchemaData();*/
//DebugLog::log($schemaDataList);
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
            //require_once("column-data-update-search-result-part.php");
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
            //require_once("column-data-update-search-result-part.php");
            ?>
        </td>
    </tr>
</table>