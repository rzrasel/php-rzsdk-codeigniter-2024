<?php
namespace App\DatabaseSchema\Data\Repositories;
?>
<?php
use App\DatabaseSchema\Domain\Repositories\DeleteDatabaseSchemaQueryRepositoryInterface;
use RzSDK\Database\SqliteConnection;
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
}
?>