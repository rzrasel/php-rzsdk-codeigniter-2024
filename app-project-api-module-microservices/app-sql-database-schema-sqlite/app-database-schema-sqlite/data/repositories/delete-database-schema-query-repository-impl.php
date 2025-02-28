<?php
namespace App\DatabaseSchema\Data\Repositories;
?>
<?php
use App\DatabaseSchema\Domain\Repositories\DeleteDatabaseSchemaQueryRepositoryInterface;
use RzSDK\Database\SqliteConnection;
use App\DatabaseSchema\Helper\Utils\DeleteActionType;
use RzSDK\Log\DebugLog;
use RzSDK\Log\LogType;
?>
<?php
class DeleteDatabaseSchemaQueryRepositoryImpl implements DeleteDatabaseSchemaQueryRepositoryInterface {
    private SqliteConnection $dbConn;

    public function __construct(SqliteConnection $dbConn = null) {
        if (is_null($dbConn)) {
            $this->dbConn = SqliteConnection::getInstance(DB_FULL_PATH);
        } else {
            $this->dbConn = $dbConn;
        }
    }

    public function onDeleteDatabaseSchemaTableData($actionType): string {
        if($actionType == DeleteActionType::DATABASE_SCHEMA_DATA) {
            $this->onDeleteCompositeKey();
            $this->onDeleteColumnKey();
            $this->onDeleteColumnData();
            $this->onDeleteTableData();
            return $this->onDeleteDatabaseSchemaData();
        } else if($actionType == DeleteActionType::TABLE_DATA) {
            $this->onDeleteCompositeKey();
            $this->onDeleteColumnKey();
            $this->onDeleteColumnData();
            return $this->onDeleteTableData();
        } else if($actionType == DeleteActionType::COLUMN_DATA) {
            $this->onDeleteCompositeKey();
            $this->onDeleteColumnKey();
            return $this->onDeleteColumnData();
        } else if($actionType == DeleteActionType::COLUMN_KEY) {
            $this->onDeleteCompositeKey();
            return $this->onDeleteColumnKey();
        } else if($actionType == DeleteActionType::COMPOSITE_KEY) {
            return $this->onDeleteCompositeKey();
        }
        return "";
    }

    private function onDeleteDatabaseSchemaData(): string {
        $sqlQuery = "DELETE FROM tbl_database_schema;";
        $this->onExecuteSqlQuery($sqlQuery);
        return "Successfully deleted database schema data.";
    }

    private function onDeleteTableData(): string {
        $sqlQuery = "DELETE FROM tbl_table_data;";
        $this->onExecuteSqlQuery($sqlQuery);
        return "Successfully deleted table data.";
    }

    private function onDeleteColumnData(): string {
        $sqlQuery = "DELETE FROM tbl_column_data;";
        $this->onExecuteSqlQuery($sqlQuery);
        return "Successfully deleted column data.";
    }

    private function onDeleteColumnKey(): string {
        $sqlQuery = "DELETE FROM tbl_column_key;";
        $this->onExecuteSqlQuery($sqlQuery);
        return "Successfully deleted column key.";
    }

    private function onDeleteCompositeKey() {
        $sqlQuery = "DELETE FROM tbl_composite_key;";
        $this->onExecuteSqlQuery($sqlQuery);
        return "Successfully deleted composite key.";
    }

    private function onExecuteSqlQuery($sqlQuery) {
        $data = [];
        $this->dbConn->execute($sqlQuery, $data);
    }
}
?>