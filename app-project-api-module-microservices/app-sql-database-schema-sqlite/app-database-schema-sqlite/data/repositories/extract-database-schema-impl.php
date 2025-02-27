<?php
namespace App\DatabaseSchema\Data\Repositories;
?>
<?php
use App\DatabaseSchema\Domain\Repositories\ExtractDatabaseSchemaInterface;
use RzSDK\Database\SqliteConnection;
?>
<?php
class ExtractDatabaseSchemaImpl implements ExtractDatabaseSchemaInterface {
    private SqliteConnection $dbConn;

    public function __construct(SqliteConnection $dbConn = null) {
        if(is_null($dbConn)) {
            $this->dbConn = SqliteConnection::getInstance(DB_FULL_PATH);
        } else {
            $this->dbConn = $dbConn;
        }
    }
}
?>