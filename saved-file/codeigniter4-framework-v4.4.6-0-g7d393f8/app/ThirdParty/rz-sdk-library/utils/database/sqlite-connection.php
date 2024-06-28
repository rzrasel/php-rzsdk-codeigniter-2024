<?php
namespace RzSDK\Database;
defined("RZ_SDK_BASEPATH") OR exit("No direct script access allowed");
defined("RZ_SDK_WRAPPER") OR exit("No direct script access allowed");
//|-----------------|CLASS - SQLITE CONNECTION|------------------|
class SqliteConnection {
    //|---------------|CLASS VARIABLE SCOPE START|---------------|
    private $pdo;
    private $sqliteFile = "sqlite-dtabase.sqlite3";
    //|----------------|CLASS VARIABLE SCOPE END|----------------|

    //|-------------------|CLASS CONSTRUCTOR|--------------------|
    public function __construct($dbPath) {
        $this->sqliteFile = $dbPath;
        $this->connect($this->sqliteFile);
    }

    //|-------------------|SQLITE CONNECTION|--------------------|
    public function connect($dbPath) {
        $this->sqliteFile = $dbPath;
        //|SQLite PDO Connection|--------------------------------|
        if ($this->pdo == null) {
            $this->pdo = new \PDO("sqlite:" . $this->sqliteFile);
        }
        /*if($this->pdo == null) {
            $this->pdo = new SQLite3('sqlite3db.db');
        }*/
        return $this->pdo;
    }

    //|-----------------------|SQL QUERY|------------------------|
    public function query($sqlQuery) {
        if ($this->pdo != null) {
            return $this->pdo->query($sqlQuery);
        }
        return null;
    }

    public function closeConnection() {
        $this->pdo = null;
    }
}
?>
<?php
/*class SQLiteConnection {
    private $pdo;
    private $sqliteFile = "bangla-to-engilsh-word.sqlite3";
    public function connect() {
        if ($this->pdo == null) {
            $this->pdo = new PDO("sqlite:" . $this->sqliteFile);
        }
        /-*if($this->pdo == null) {
            $this->pdo = new SQLite3('sqlite3db.db');
        }*-/
        return $this->pdo;
    }
}*/
?>