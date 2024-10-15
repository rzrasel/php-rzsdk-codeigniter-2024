<?php
namespace RzSDK\DatabaseSpace;
?>
<?php
use RzSDK\DatabaseSpace\DbType;
use RzSDK\DatabaseSpace\UserRegistrationTable;
use RzSDK\Log\DebugLog;
?>
<?php
class UserRegistrationTableQuery extends UserRegistrationTable {
    private DbType $dbType;

    public function __construct(DbType $dbType) {
        $this->dbType = $dbType;
        //$this->execute(DbType::SQLITE);
    }

    public function dropQuery() {
        $table = parent::$table;
        return "DROP TABLE IF EXISTS " . $table . ";";
    }

    public function deleteQuery() {
        $table = parent::$table;
        return "DELETE FROM " . $table . ";";
    }

    public function execute() {
        $table = parent::$table;
        $columns = $this->getColumn();
        $columnsWithKey = $this->getColumnWithKey();
        //DebugLog::log($column);
        //$this->{$column[0]}();
        $sqlQuery = "";
        if($this->dbType == DbType::SQLITE) {
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
            "user_regi_id"      => "BIGINT(20) NOT NULL",
            "email"             => "TEXT NOT NULL",
            "status"            => "BOOLEAN NOT NULL DEFAULT TRUE",
            "is_verified"       => "BOOLEAN NOT NULL DEFAULT FALSE",
            "regi_date"         => "DATETIME NOT NULL",
            "device_type"       => "VARCHAR(32) NOT NULL",
            "auth_type"         => "VARCHAR(32) NOT NULL",
            "agent_type"        => "VARCHAR(32) NOT NULL",
            "regi_os"           => "VARCHAR(32) NULL",
            "regi_device"       => "VARCHAR(32) NULL",
            "regi_browser"      => "VARCHAR(32) NULL",
            "regi_ip"           => "VARCHAR(32) NOT NULL",
            "regi_http_agent"   => "TEXT NOT NULL",
            "modified_by"       => "BIGINT(20) NOT NULL",
            "created_by"        => "BIGINT(20) NOT NULL",
            "modified_date"     => "DATETIME NOT NULL",
            "created_date"      => "DATETIME NOT NULL",
        );
    }
}
?>