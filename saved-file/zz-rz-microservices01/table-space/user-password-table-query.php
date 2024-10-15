<?php
namespace RzSDK\DatabaseSpace;
?>
<?php
use RzSDK\DatabaseSpace\DbType;
use RzSDK\DatabaseSpace\UserPasswordTable;
use RzSDK\Log\DebugLog;
?>
<?php
class UserPasswordTableQuery extends UserPasswordTable {
    public function __construct() {
        //$this->execute(DbType::SQLITE);
    }

    public function dropQuery(DbType $dbType) {
        $table = parent::$table;
        return "DROP TABLE IF EXISTS " . $table . ";";
    }

    public function deleteQuery(DbType $dbType) {
        $table = parent::$table;
        return "DELETE FROM " . $table . ";";
    }

    public function execute(DbType $dbType) {
        $table = parent::$table;
        $columns = $this->getColumn();
        $columnsWithKey = $this->getColumnWithKey();

        $sqlQuery = "";
        if($dbType == DbType::SQLITE) {
            $columProperties = $this->getSQLiteColumnProperty();
            if(count($columnsWithKey) != count($columProperties)) {
                //DebugLog::log("Error column size is not same, column size: " . count($columnsWithKey) . ", column property size: " . count($columProperties));
                return "Error {$table} column size is not same, column size: " . count($columnsWithKey) . ", column property size: " . count($columProperties);
            }
            $keyLength = -1;
            foreach ($columnsWithKey as $key => $value) {
                if(strlen($key) > $keyLength) {
                    $keyLength = strlen($key);
                }
            }
            $maxSpace = $keyLength + 4;
            $sqlQuery = "";
            $sqlQuery .= "CREATE TABLE IF NOT EXISTS " . $table . " (" . "";
            $sqlQuery .= "<br />";
            foreach ($columnsWithKey as $key => $value) {
                $sqlQuery .= "    " . str_pad($key, $maxSpace, " ") . $columProperties[$key] . ",";
                $sqlQuery .= "<br />";
            }
            $sqlQuery .= "    " . "CONSTRAINT "
            . "pk_{$table}_{$columns['0']} PRIMARY KEY ({$columns[0]})"
            . "";
            $sqlQuery .= "<br />";
            $sqlQuery .= ");";
        }
        //DebugLog::log($sqlQuery);
        return $sqlQuery;
    }

    private function getSQLiteColumnProperty() {
        return array(
            "user_id"       => "BIGINT(20) NOT NULL",
            "password"      => "TEXT NOT NULL",
            "status"        => "BOOLEAN NOT NULL DEFAULT TRUE",
            "modified_by"   => "BIGINT(20) NOT NULL",
            "created_by"    => "BIGINT(20) NOT NULL",
            "modified_date" => "DATETIME NOT NULL",
            "created_date"  => "DATETIME NOT NULL",
        );
    }
}
?>