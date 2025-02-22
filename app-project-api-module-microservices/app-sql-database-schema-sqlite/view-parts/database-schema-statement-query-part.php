<?php
namespace App\DatabaseSchema\Sql;
?>
<?php
require_once("include.php");
?>
<?php
use App\DatabaseSchema\Domain\Models\DatabaseSchemaModel;
use App\DatabaseSchema\Helper\Database\Data\Retrieve\DbRetrieveDatabaseSchemaData;
use App\DatabaseSchema\Sql\Builder\SqliteSqlBuilder;
use RzSDK\Log\DebugLog;
?>
<?php
?>
<?php
//app-sys-database-tables-schema
$schemaId = "174014882064916708";
/*//user_management
$schemaId = "174014882064916708";
//quiz_manager_database_schema
$schemaId = "174014407593278352";*/
$dbRetrieveSchemaData = (new DbRetrieveDatabaseSchemaData())->getAllDatabaseSchemaData($schemaId);
//DebugLog::log($dbRetrieveSchemaData);
/*$databaseSchemaGenerate = new DatabaseSchemaStatementGenerate($dbRetrieveSchemaData);
$databaseSchemaGenerate->getDatabaseSchema();*/
$sql = "";
if($dbRetrieveSchemaData) {
    $sqlBuilder = new SqliteSqlBuilder();
    $sql = $sqlBuilder->buildSql($dbRetrieveSchemaData);
}

/*echo "<pre>"; // For formatted output in HTML
echo $sql;
echo "</pre>";*/
?>
<table class="database-schema-content">
    <tr>
        <td>
            <pre>
                <?= $sql; ?>
            </pre>
        </td>
    </tr>
</table>