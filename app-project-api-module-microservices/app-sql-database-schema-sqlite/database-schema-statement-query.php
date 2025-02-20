<?php
?>
<?php
require_once("include.php");
?>
<?php
use App\DatabaseSchema\Domain\Models\DatabaseSchemaModel;
use App\DatabaseSchema\Helper\Database\Data\Retrieve\DbRetrieveDatabaseSchemaData;
use RzSDK\Log\DebugLog;
?>
<?php
class DatabaseSchemaStatementGenerate {
    private $databaseSchemas;
    //

    public function __construct(array $databaseSchemas) {
        $this->databaseSchemas = $databaseSchemas;
        //DebugLog::log($this->databaseSchemas);
    }

    public function setSchemas(array $databaseSchemas): void {
        $this->databaseSchemas = $databaseSchemas;
    }

    public function getDatabaseSchema() {
        foreach($this->databaseSchemas as $schemaIndex => $schemaItem) {
            $schemaItem = self::getDatabaseSchemaModel($schemaItem);
            echo $schemaItem->schemaVersion;
            foreach($schemaItem->tableDataList as $tableIndex => $tableItem) {}
        }
    }

    public static function getDatabaseSchemaModel(DatabaseSchemaModel $databaseSchema): DatabaseSchemaModel {
        return $databaseSchema;
    }
}
?>
<?php
$dbRetrieveSchemaData = (new DbRetrieveDatabaseSchemaData())->getAllDatabaseSchemaData();
$databaseSchemaGenerate = new DatabaseSchemaStatementGenerate($dbRetrieveSchemaData);
$databaseSchemaGenerate->getDatabaseSchema();
?>
