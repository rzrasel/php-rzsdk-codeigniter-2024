<?php
use App\DatabaseSchema\Helper\Database\Data\Retrieve\DatabaseSchemaRawQuery;
use App\DatabaseSchema\Domain\Models\DatabaseSchemaModel;
use App\DatabaseSchema\Domain\Models\TableDataModel;
use App\DatabaseSchema\Domain\Models\ColumnDataModel;
use App\DatabaseSchema\Domain\Models\ColumnKeyModel;
use App\DatabaseSchema\Domain\Models\CompositeKeyModel;
use App\DatabaseSchema\Data\Entities\DatabaseSchema;
use App\DatabaseSchema\Data\Entities\TableData;
use App\DatabaseSchema\Data\Entities\ColumnData;
use App\DatabaseSchema\Data\Entities\ColumnKey;
use App\DatabaseSchema\Data\Entities\CompositeKey;
use App\DatabaseSchema\Helper\Database\Data\Retrieve\DbRetrieveDatabaseSchemaData;
use App\DatabaseSchema\Html\Select\DropDown\HtmlSelectDropDown;
use App\DatabaseSchema\Schema\Build\RawQuery\BuildDatabaseSchemaRawQuery;
use RzSDK\Log\DebugLog;
?>
<?php
?>
<?php
$selectedSchemaId = "";
$schemaId = "";
if(!empty($_REQUEST)) {
    //DebugLog::log($_REQUEST);
    $selectedSchemaId = $_REQUEST["search_by_schema_id"];
    $schemaId = $_REQUEST["search_by_schema_id"];
}
?>
<?php
$rawQueryOutput = array(
    "table" => [],
    "data" => "",
);
if(!empty($schemaId)) {
    $rawQueryOutput = array();
    $buildSchemaRawQuery = new BuildDatabaseSchemaRawQuery();
    //$rawQueryOutput = $buildSchemaRawQuery->buildRecursiveDatabaseSchemaRawQuery($schemaId, "", "");
    $rawQueryOutput = $buildSchemaRawQuery->buildDatabaseSchemaRawQuery($schemaId, "", "");
}
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
<table class="form-heading">
    <tr>
        <td></td>
    </tr>
    <tr>
        <td>Database Schema Raw Query Statement</td>
    </tr>
    <tr>
        <td></td>
    </tr>
</table>
<br />
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
                <?= "\n\n{$rawQueryOutput["data"]}\n\n"; ?>
            </pre>
        </td>
    </tr>
</table>