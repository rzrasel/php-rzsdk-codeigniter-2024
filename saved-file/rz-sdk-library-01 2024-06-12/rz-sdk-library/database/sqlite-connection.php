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
            try {
                $this->pdo = new \PDO("sqlite:" . $this->sqliteFile);
                $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
                $this->pdo->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
            } catch(\PDOException $e) {
                die($e->getMessage());
            }
        }
        /*if($this->pdo == null) {
            $this->pdo = new SQLite3('sqlite3db.db');
        }*/
        return $this->pdo;
    }

    //|-----------------------|SQL QUERY|------------------------|
    public function query($sqlQuery) {
        $query = preg_replace("/escape_string\((.*?)\)/", $this->escapeString("$1"), $sqlQuery);
        if ($this->pdo != null) {
            return $this->pdo->query($query);
        }
        return null;
    }

    public function closeConnection() {
        $this->pdo = null;
    }

    function escapeString($string, $quotestyle = "both") {

		if(function_exists("sqlite_escape_string")) {
			$string = sqlite_escape_string($string);
			$string = str_replace("''","'",$string); #- no quote escaped so will work like with no sqlite_escape_string available
		} else {
			$escapes = array("\x00", "\x0a", "\x0d", "\x1a", "\x09","\\");
			$replace = array('\0',   '\n',    '\r',   '\Z' , '\t',  "\\\\");
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
		return str_replace($escapes,$replace,$string);
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
/*

https://github.com/archytech99/mvc-sqlite

https://github.com/tbreuss/pdo/blob/main/src/PDO.php

https://github.com/lampaa/SQLite/blob/master/sqlite.class.php#L126

*/
?>