<?php
namespace RzSDK\Database;
?>
<?php
use PDO;
use PDOException;
use PDOStatement;
use RzSDK\Database\SqliteFetchType;
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
    public function query($sqlQuery, SqliteFetchType $fetchMode = SqliteFetchType::NONE) {
        //$fetchMode->valueDebugLog::log($fetchMode->value);
        if ($this->pdo !== null) {
            $query = preg_replace("/escape_string\((.*?)\)/", $this->escapeString("$1"), $sqlQuery);
            try {
                if($fetchMode == SqliteFetchType::NONE) {
                    return $this->pdo->query($query);
                } else {
                    $statement = $this->pdo->query($query);
                    return $statement->fetch($fetchMode->value);
                }
                //return $this->pdo->query($query);
            } catch (PDOException $e) {
                //error_log("SQL Query Error: " . $e->getMessage());
                DebugLog::log("SQL query error: " . $e->getMessage(), LogType::WARNING, true, 0, __CLASS__);
                return false;
            }
        }
        return false;
    }

    public function fetch($results, SqliteFetchType $fetchMode = SqliteFetchType::FETCH_DEFAULT) {
        return $results->fetch($fetchMode);
    }

    //|-------------------|QUERY EXECUTE SQL QUERY|--------------------|
    // Secure Query Execution with Parameters
    public function queryExecute($sqlQuery, $params = []) {
        //DebugLog::log("Error executing SQL Query: " . __CLASS__, LogType::ERROR, true, 2);
        $params = self::bindValues($params);
        //DebugLog::log($params);
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
        //DebugLog::log($sqlQuery);
        $params = [":value" => $value];

        try {
            $stmt = $this->pdo->prepare($sqlQuery);
            $stmt->execute($params);
            $result = $stmt->fetch(\PDO::FETCH_ASSOC);

            return ($result["count"] > 0);
        } catch (PDOException $e) {
            error_log("SQL Query Error: " . $e->getMessage());
            return false;
        }
    }

    public function isDataExistsMultiple(string $table, array $conditions): bool {
        if($this->pdo === null) {
            return false;
        }

        $whereClauses = [];
        $params = [];
        foreach ($conditions as $column => $value) {
            $whereClauses[] = "$column = :$column";
            $params[":$column"] = $value;
        }

        $sqlQuery = "SELECT COUNT(*) as count FROM $table WHERE " . implode(' AND ', $whereClauses);

        try {
            $stmt = $this->pdo->prepare($sqlQuery);
            $stmt->execute($params);
            $result = $stmt->fetch(\PDO::FETCH_ASSOC);

            return ($result["count"] > 0);
        } catch (PDOException $e) {
            //error_log("SQL Query Error: " . $e->getMessage());
            DebugLog::log("SQL query error: " . $e->getMessage(), LogType::WARNING, true, 0, __CLASS__);
            return false;
        }
    }

    public function isDataExistsWithJoin(string $mainTable, array $joins, array $conditions): bool {
        if($this->pdo === null) {
            return false;
        }

        $joinClauses = [];
        foreach ($joins as $join) {
            $joinClauses[] = "{$join['type']} JOIN {$join['table']} ON {$join['on']}";
        }

        $whereClauses = [];
        $params = [];
        foreach ($conditions as $column => $value) {
            $whereClauses[] = "$column = :$column";
            $params[":$column"] = $value;
        }

        $sqlQuery = "SELECT COUNT(*) as count FROM $mainTable " . implode(' ', $joinClauses) . " WHERE " . implode(' AND ', $whereClauses);

        try {
            $stmt = $this->pdo->prepare($sqlQuery);
            $stmt->execute($params);
            $result = $stmt->fetch(\PDO::FETCH_ASSOC);

            return ($result["count"] > 0);
        } catch (PDOException $e) {
            //error_log("SQL Query Error: " . $e->getMessage());
            DebugLog::log("SQL query error: " . $e->getMessage(), LogType::WARNING, true, 0, __CLASS__);
            return false;
        }
    }

    public static function bindValues(array $dataSet) {
        $itemValue = "";
        $dataList = array();
        //DebugLog::log($dataSet);
        foreach($dataSet as $key => $value) {
            $itemValue = self::bindValue($value, $key);
            $dataList[$key] = $itemValue;
        }
        return $dataList;
        /*foreach(array_values($this->tableData) as $item) {
            if(is_bool($item)) {
                if($item) {
                    $values .= "TRUE, ";
                } else {
                    $values .= "FALSE, ";
                }
            } else if(empty($item)) {
                if(is_int($item) || is_numeric($item)) {
                    $values .= "'" . $item . "', ";
                } else {
                    $values .= "NULL, ";
                }
                //$values .= "NULL, ";
            } else if(is_int($item) || is_numeric($item)) {
                if(is_string($item)) {
                    $values .= "'" . $item . "', ";
                } else {
                    $values .= "" . $item . ", ";
                }
            } else if(is_bool($item)) {
                if($item) {
                    $values .= "TRUE, ";
                } else {
                    $values .= "FALSE, ";
                }
            } else {
                $values .= "'" . $item . "', ";
            }
        }*/
        //$values = trim(trim($values), ",");
        //return $values;
    }

    public static function bindValueV3($value, $key = "") {
        DebugLog::log("$key - $value");
        if(!isset($value)) {
            //DebugLog::log("$key - isset");
        }
        if($value == 0 || $value == "0" || $value === 0 || $value === "0") {
            return "0";
        }
        if(is_bool($value)) {
            return $value ? 'TRUE' : 'FALSE';
        }

        if(is_int($value) || is_numeric($value)) {
            return $value;
        }
        // Handle NULL explicitly
        if(is_null($value)) {
            return NULL;
        }

        // Handle booleans
        if(is_bool($value)) {
            return $value ? 'TRUE' : 'FALSE';
        }

        // Handle numeric values (integers and floats)
        if(is_numeric($value)) {
            return (string) $value;
        }

        // Handle boolean-like string values
        $lowerValue = strtolower((string)$value);
        if(in_array($lowerValue, ['true', 'false'], true)) {
            return $lowerValue === 'true' ? 'TRUE' : 'FALSE';
        }

        return addslashes($value);

        // Escape and return strings
        //return "'" . addslashes($value) . "'";
    }

    public static function isBooleanV3($value): bool {
        return is_bool($value) || in_array(strtolower((string)$value), ['true', 'false'], true);
    }

    public static function bindValueV2($value, $key = "") {
        if(is_bool($value)) {
            return $value ? 'TRUE' : 'FALSE';
        }

        if(is_int($value) || is_numeric($value)) {
            return (string)$value;
        }

        if(is_null($value)) {
            return NULL;
        }

        if(self::isBoolean($value)) {
            return strtolower($value) == 'true' ? 'TRUE' : 'FALSE';
        }

        return (string)$value;
    }

    public static function isBooleanV2($value): bool {
        return is_bool($value) || in_array(strtolower($value), ['true', 'false']);
    }

    public static function bindValue($value, $key = "") {
        $itemValue = "";
        if(empty($value)) {
            $itemValue = '';
            if(is_null($value)) {
                $itemValue = NULL;
            }
        } else if(self::isBoolean($value)) {
            $itemValue = 'FALSE';
            if($value && strtolower("$value") == 'true') {
                $itemValue = 'TRUE';
            }
        } else if(is_int($value) || is_numeric($value)) {
            if(is_string($value)) {
                $itemValue = "$value";
            } else {
                $itemValue = $value;
            }
        } else {
            $itemValue = "$value";
            if(self::isBoolean($value)) {
                $itemValue = 'FALSE';
                if($value && strtolower("$value") == 'true') {
                    $itemValue = 'TRUE';
                }
            }
        }
        /*echo "$key: $value = " . gettype($value);
        echo "<br />";*/
        return $itemValue;
    }
    public static function isBoolean($value): bool {
        if(is_bool($value) || strtolower($value) == "true" || strtolower($value) == "false") {
            return true;
        }
        return false;
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

if($db->isDataExists($table, $column, $value)) {
    echo "User with email $value exists in the database.";
} else {
    echo "User with email $value does not exist in the database.";
}*/

/*Multiple Columns and Values
$conditions = [
    'column1' => 'value1',
    'column2' => 'value2',
];

$exists = $sqliteConnection->isDataExistsMultiple('your_table', $conditions);*/

/*Extending for Multiple Tables (Joins)
              $joins = [
                  [
                      'type' => 'INNER',
                      'table' => 'other_table',
                      'on' => 'main_table.id = other_table.main_id',
                  ],
              ];

$conditions = [
    'main_table.column1' => 'value1',
    'other_table.column2' => 'value2',
];

$exists = $sqliteConnection->isDataExistsWithJoin('main_table', $joins, $conditions);*/
?>
