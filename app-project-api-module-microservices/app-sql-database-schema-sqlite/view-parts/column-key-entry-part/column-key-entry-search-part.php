<?php
use App\DatabaseSchema\Helper\Database\Data\Retrieve\DbRetrieveDatabaseSchemaData;
use App\DatabaseSchema\Html\Select\DropDown\HtmlSelectDropDown;
use RzSDK\Log\DebugLog;
?>
<?php
global $schemaDataList, $selectedTableId;
global $schemaDataList;
$selectedSchemaId = "";
$selectedTableId = "";
?>
<?php
$dbRetrieveDatabaseSchemaData = new DbRetrieveDatabaseSchemaData();
?>
<?php
$dbSchemaDataListOnly = $dbRetrieveDatabaseSchemaData->getAllDatabaseSchemaDataOnly();
//DebugLog::log($dbSchemaDataListOnly);
?>
<?php
if(!empty($_REQUEST)) {
    //DebugLog::log($_REQUEST);
    $selectedSchemaId = $_REQUEST["search_by_schema_id"];
}
$schemaSelectDropDown = HtmlSelectDropDown::schemaSelectDropDown("search_by_schema_id", $dbSchemaDataListOnly, $selectedSchemaId);
?>
<?php
$schemaDataList = $dbRetrieveDatabaseSchemaData->getAllDatabaseSchemaData($selectedSchemaId);
?>
<?php
if(!empty($_REQUEST)) {
    if(!empty($_REQUEST["search_by_table_id"])) {
        $selectedTableId = $_REQUEST["search_by_table_id"];
    }
}
?>
<?php
$tableSelectDropDown = HtmlSelectDropDown::tableSelectDropDown("search_by_table_id", $schemaDataList, $selectedTableId);
?>
<form action="<?= $_SERVER["PHP_SELF"]; ?>" method="GET">
    <table valign="right" width="100%" class="table-search-by-fields">
        <tr>
            <td>Search By:</td>
            <td width="10px"></td>
            <td><?= $schemaSelectDropDown; ?></td>
            <td width="10px"></td>
            <td><?= $tableSelectDropDown; ?></td>
            <td width="10px"></td>
            <td class="button-search"><button type="submit">Search</button></td>
        </tr>
    </table>
</form>