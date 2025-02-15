<?php
// app/Config/Database.php
namespace App\Config;
?>
<?php
use PDO;
use PDOException;
?>
<?php
class Database {
    /*public static function getConnection(): PDO {
        return new PDO('sqlite:database.sqlite3');
    }*/
    private static ?PDO $instance = null;

    private function __construct() {}

    public static function getInstance($databasePath): PDO {
        if (self::$instance === null) {
            $dsn = 'sqlite:' . $databasePath;
            self::$instance = new PDO($dsn);
            self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }

        return self::$instance;
    }

    public static function getConnection($databasePath = "database.sqlite3"): PDO {
        if (self::$instance === null) {
            try {
                $dsn = 'sqlite:' . $databasePath;
                self::$instance = new PDO($dsn);
                self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                //die("Database connection failed: " . $e->getMessage());
                error_log("SQL Query Error: " . $e->getMessage());
            }
        }
        return self::$instance;
    }
}
?>