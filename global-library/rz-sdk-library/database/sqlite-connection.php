<?php
namespace RzSDK\Database;
?>
<?php
use PDO;
use PDOException;
use PDOStatement;
use RzSDK\Log\DebugLog;
use RzSDK\Log\LogType;

?>
<?php
class SqliteConnection {
    //|---------------|CLASS VARIABLE SCOPE START|---------------|
    private static ?SqliteConnection $instance = null;
    private ?PDO $pdo = null;
    private $sqliteFile = "sqlite-database.sqlite3";
    //|----------------|CLASS VARIABLE SCOPE END|----------------|

    //|-------------------|CLASS CONSTRUCTOR|--------------------|
    private function __construct($dbPath) {
        $this->sqliteFile = $dbPath;
        $this->connect($this->sqliteFile);
    }

    public static function getInstance($dbPath): SqliteConnection {
        if(self::$instance === null || !isset(self::$instance)) {
            self::$instance = new self($dbPath);
        }
        return self::$instance;
    }

    //|-------------------|SQLITE CONNECTION|--------------------|
    public function connect($dbPath) {
        $this->sqliteFile = $dbPath;
        if (!file_exists($this->sqliteFile)) {
            //touch($this->sqliteFile); // Create the file if it doesn't exist
            DebugLog::log("Database file not found: " . $this->sqliteFile, LogType::ERROR, true, 0, __CLASS__);
            return false;
        }
        //|SQLite PDO Connection|--------------------------------|
        if($this->pdo == null) {
            try {
                $this->pdo = new PDO("sqlite:" . $this->sqliteFile);
                $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            } catch(PDOException $e) {
                //error_log("SQL Query Error: " . $e->getMessage());
                DebugLog::log("PDO initialization error: " . $e->getMessage(), LogType::WARNING);
                return false;
            }
        }
        return $this->pdo;
    }

    //|-----------------------|SQL QUERY|------------------------|
    public function query($sqlQuery) {
        if ($this->pdo !== null) {
            $query = preg_replace("/escape_string\((.*?)\)/", $this->escapeString("$1"), $sqlQuery);
            try {
                return $this->pdo->query($query);
            } catch (PDOException $e) {
                //error_log("SQL Query Error: " . $e->getMessage());
                DebugLog::log("SQL query error: " . $e->getMessage(), LogType::WARNING, true, 0, __CLASS__);
                return false;
            }
        }
        return false;
    }

    //|-------------------|QUERY EXECUTE SQL QUERY|--------------------|
    // Secure Query Execution with Parameters
    public function queryExecute($sqlQuery, $params = []) {
        //DebugLog::log("Error executing SQL Query: " . __CLASS__, LogType::ERROR, true, 2);
        if ($this->pdo !== null) {
            $query = preg_replace("/escape_string\((.*?)\)/", $this->escapeString("$1"), $sqlQuery);
            try {
                $stmt = $this->pdo->prepare($sqlQuery);
                $stmt->execute($params);
                return $stmt;
            } catch (PDOException $e) {
                //error_log("SQL Query Error: " . $e->getMessage());
                //echo "Database Error: " . $e->getMessage();
                DebugLog::log("SQL query error: " . $e->getMessage(), LogType::WARNING, true, 0, __CLASS__);
                return false;
            }
        }
        return false;
    }

    //|-------------------|EXECUTE SQL QUERY|--------------------|
    public function execute($sqlQuery, $params = []) {
        return $this->queryExecute($sqlQuery, $params);
    }

    //|-------------------|RETURN INSERT ID|---------------------|
    public function getLastInsertId() {
        if($this->pdo != null) {
            return $this->pdo->lastInsertId();
        }
        return null;
    }

    //|-------------------|CLOSE CONNECTION|---------------------|
    public function closeConnection() {
        $this->pdo = null;
    }

    //|-------------------|ESCAPE STRING|-----------------------|
    public function escapeString($string, $quotestyle = "both") {
        if(function_exists("sqlite_escape_string")) {
            $string = sqlite_escape_string($string);
            $string = str_replace("''", "'", $string); // No quote escaped so will work like with no sqlite_escape_string available
        } else {
            $escapes = array("\x00", "\x0a", "\x0d", "\x1a", "\x09", "\\");
            $replace = array('\0', '\n', '\r', '\Z', '\t', "\\\\");
        }
        switch(strtolower($quotestyle)) {
            case 'double':
            case 'd':
            case '"':
                $escapes[] = '"';
                $replace[] = '\"';
                break;
            case 'single':
            case 's':
            case "'":
                $escapes[] = "'";
                $replace[] = "''";
                break;
            case 'both':
            case 'b':
            case '"\'':
            case '\'"':
                $escapes[] = '"';
                $replace[] = '\"';
                $escapes[] = "'";
                $replace[] = "''";
                break;
        }
        return str_replace($escapes, $replace, $string);
    }
    //
    public function escapeStringPdo($string) {
        return $this->pdo ? $this->pdo->quote($string) : addslashes($string);
    }

    //|-------------------|CHECK IF DATA EXISTS|-----------------|
    /**
     * Check if data exists in the database based on a condition.
     *
     * @param string $table The table name.
     * @param string $column The column to check.
     * @param mixed $value The value to match.
     * @return bool True if data exists, false otherwise.
     */
    public function isDataExists(string $table, string $column, $value): bool {
        if($this->pdo === null) {
            return false;
        }

        $sqlQuery = "SELECT COUNT(*) as count FROM $table WHERE $column = :value";
        $params = [':value' => $value];

        try {
            $stmt = $this->pdo->prepare($sqlQuery);
            $stmt->execute($params);
            $result = $stmt->fetch(\PDO::FETCH_ASSOC);

            return ($result['count'] > 0);
        } catch (PDOException $e) {
            error_log("SQL Query Error: " . $e->getMessage());
            return false;
        }
    }
}
?>
<?php
/*require_once 'SqliteConnection.php';

use RzSDK\Database\SqliteConnection;

// Initialize the SQLite connection
$dbPath = "path/to/your/database.sqlite3";
$sqliteConnection = SqliteConnection::getInstance($dbPath);

// Insert data into a table
$sqlQuery = "INSERT INTO tbl_table_data (schema_id, table_name, modified_date, created_date) 
             VALUES (:schema_id, :table_name, :modified_date, :created_date)";
$params = [
    ':schema_id' => 1,
    ':table_name' => 'users',
    ':modified_date' => date('Y-m-d H:i:s'),
    ':created_date' => date('Y-m-d H:i:s')
];

// Execute the query
$sqliteConnection->execute($sqlQuery, $params);

// Get the last inserted ID
$lastInsertId = $sqliteConnection->getLastInsertId();
echo "Last Inserted ID: " . $lastInsertId;

// Close the connection
$sqliteConnection->closeConnection();*/

/*require_once 'SqliteConnection.php';
use RzSDK\Database\SqliteConnection;

$dbPath = "database.sqlite3";
$db = SqliteConnection::getInstance($dbPath);

$sqlQuery = "SELECT * FROM users WHERE id = :id";
$params = [':id' => 1];

$stmt = $db->query($sqlQuery, $params);
$result = $stmt->fetchAll(); // Fetch all rows

print_r($result);*/

/*$sqlQuery = "INSERT INTO users (name, email) VALUES (:name, :email)";
$params = [
    ':name' => 'John Doe',
    ':email' => 'john@example.com'
];

$db->execute($sqlQuery, $params);
$lastInsertId = $db->getLastInsertId();

echo "Last Inserted ID: " . $lastInsertId;*/
/*require_once 'SqliteConnection.php';
use RzSDK\Database\SqliteConnection;

$dbPath = "database.sqlite3";
$db = SqliteConnection::getInstance($dbPath);

$table = "users";
$column = "email";
$value = "john@example.com";

if ($db->isDataExists($table, $column, $value)) {
    echo "User with email $value exists in the database.";
} else {
    echo "User with email $value does not exist in the database.";
}*/
?>
