<?php
namespace Core;
?>
<?php
use Core\DatabaseType;
?>
<?php
class Database {
    private $connection;

    public function __construct(DatabaseType $type, string $host = null, string $username = null, string $password = null, string $database = null, string $sqlitePath = null) {
        switch ($type) {
            case DatabaseType::MYSQL:
                $this->connection = new \mysqli($host, $username, $password, $database);
                if ($this->connection->connect_error) {
                    die("MySQL Connection failed: " . $this->connection->connect_error);
                }
                break;
            case DatabaseType::SQLITE:
                $this->connection = new \SQLite3($sqlitePath);
                if (!$this->connection) {
                    die("SQLite Connection failed.");
                }
                break;
            default:
                throw new \InvalidArgumentException("Unsupported database type.");
        }
    }

    public function getConnection() {
        return $this->connection;
    }
}
?>