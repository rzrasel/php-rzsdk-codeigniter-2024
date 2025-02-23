<?php
namespace App\DatabaseSchema\Sql;
?>
<?php
require_once("include.php");
?>
<?php
use App\DatabaseSchema\Domain\Models\DatabaseSchemaModel;
use App\DatabaseSchema\Helper\Database\Data\Retrieve\DbRetrieveDatabaseSchemaData;
use App\DatabaseSchema\Html\Select\DropDown\HtmlSelectDropDown;
use App\DatabaseSchema\Sql\Builder\SqliteSqlBuilder;
use RzSDK\Log\DebugLog;
?>
<?php
$dbRetrieveSchemaData = (new DbRetrieveDatabaseSchemaData())->getAllDatabaseSchemaDataOnly();
//DebugLog::log($dbRetrieveSchemaData);
?>
<?php
$selectedSchemaId = "";
$schemaId = "";
if(!empty($_REQUEST)) {
    //DebugLog::log($_REQUEST);
    $selectedSchemaId = $_REQUEST["search_by_schema_id"];
    $schemaId = $_REQUEST["search_by_schema_id"];
}
if(!empty($dbRetrieveSchemaData)) {
    $schemaSelectDropDown = HtmlSelectDropDown::schemaSelectDropDown("search_by_schema_id", $dbRetrieveSchemaData, $selectedSchemaId);
}
?>
<?php
//app-sys-database-tables-schema
//$schemaId = "174014882064916708";
if(!empty($selectedSchemaId)) {
    $dbRetrieveSchemaData = (new DbRetrieveDatabaseSchemaData())->getAllDatabaseSchemaData($schemaId);
}
//DebugLog::log($dbRetrieveSchemaData);
/*$databaseSchemaGenerate = new DatabaseSchemaStatementGenerate($dbRetrieveSchemaData);
$databaseSchemaGenerate->getDatabaseSchema();*/
$sql = "";
if(!empty($schemaId)) {
    if ($dbRetrieveSchemaData) {
        $sqlBuilder = new SqliteSqlBuilder();
        $sql = $sqlBuilder->buildSql($dbRetrieveSchemaData);
    }
}

/*echo "<pre>"; // For formatted output in HTML
echo $sql;
echo "</pre>";*/
?>
<form action="<?= $_SERVER["PHP_SELF"]; ?>" method="GET">
    <table valign="right" width="100%" class="table-search-by-fields">
        <tr>
            <td>Search By:</td>
            <td width="10px"></td>
            <td><?= $schemaSelectDropDown; ?></td>
            <td width="10px"></td>
            <td class="button-search"><button type="submit">Generate SQL Statement</button></td>
        </tr>
    </table>
</form>
<br />
<table class="database-schema-content">
    <tr>
        <td>
            <pre>
                <?= $sql; ?>
            </pre>
        </td>
    </tr>
</table>