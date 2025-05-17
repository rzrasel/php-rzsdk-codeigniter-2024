<?php
namespace App\Microservice\Core\Utils\Database;
?>
<?php
use App\Microservice\Core\Utils\Type\Database\DatabaseType;
use RzSDK\Database\SqliteConnection;
?>
<?php
class Database {
    //private SqliteConnection $dbConn;
    private $dbConn;
    //
    public function __construct() {
        $databaseType = DATABASE_TYPE;
        switch($databaseType) {
            case DatabaseType::MYSQL:
                break;
            case DatabaseType::SQLITE:
                $this->dbConn = $this->getSqliteDbConn();
                break;
            default:
                $this->dbConn = $this->getSqliteDbConn();
        }
    }

    public function getConnection() {
        return $this->dbConn;
    }

    private function getSqliteDbConn(): SqliteConnection {
        if(is_null($this->dbConn)) {
            return SqliteConnection::getInstance(DB_FULL_PATH);
        }
        return $this->dbConn;
    }
}
?>