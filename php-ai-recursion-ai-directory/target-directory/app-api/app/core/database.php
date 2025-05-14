<?php
namespace App\Core;
?>
<?php
use PDO;
?>
<?php
class Database {
    public static function connect() {
        $dbType = DB_TYPE;
        $dbConnection = null;

        /*try {
            if ($dbType === 'mysql') {
                $dbConnection = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
            } elseif ($dbType === 'sqlite') {
                $dbConnection = new PDO("sqlite:" . DB_SQLITE_PATH);
            }
            $dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (\Exception $e) {
            die("Database connection failed: " . $e->getMessage());
        }*/

        return $dbConnection;
    }
}
?>