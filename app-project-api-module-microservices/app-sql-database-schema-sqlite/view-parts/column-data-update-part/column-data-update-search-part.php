<?php
use App\DatabaseSchema\Helper\Database\Data\Retrieve\DbRetrieveDatabaseSchemaData;
use App\DatabaseSchema\Html\Select\DropDown\HtmlSelectDropDown;
?>
<?php
global $schemaDataList, $selectedTableId;
$selectedTableId = "";
?>
<?php
$dbRetrieveDatabaseSchemaData = new DbRetrieveDatabaseSchemaData();
global $schemaDataList;
$schemaDataList = $dbRetrieveDatabaseSchemaData->getAllDatabaseSchemaData();
?>
<?php
if(!empty($_REQUEST)){
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
            <td><?= $tableSelectDropDown; ?></td>
            <td width="10px"></td>
            <td class="button-search"><button type="submit">Search</button></td>
        </tr>
    </table>
</form>