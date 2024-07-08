<?php
namespace RzSDK\DatabaseSpace;
?>
<?php
use RzSDK\DatabaseSpace\DbTableProperty;
use RzSDK\DatabaseSpace\DbColumnProperties;
use RzSDK\DatabaseSpace\DbColumnConstraintsProperties;
use RzSDK\DatabaseSpace\DbColumnConstraintType;
use RzSDK\DatabaseSpace\DbSqlQueryGenerator;
use RzSDK\Log\DebugLog;
?>
<?php
class UserLoginAuthLogTableQuery extends UserLoginAuthLogTable {
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
        $tableProperties = $this->getSQLiteColumnProperty();
        if(empty($tableProperties)) {
            return "Error from class " . __CLASS__ . " method " . __METHOD__;
        }
        //DebugLog::log($tableProperties);
        $dbSqlQueryGenerator = new DbSqlQueryGenerator($tableProperties);
        $sqlQuery = $dbSqlQueryGenerator->build();
        //DebugLog::log($sqlQuery);
        return $sqlQuery;
    }

    private function getSQLiteColumnProperty() {
        $tablePropertyList = array(
            "user_id"           => "BIGINT(20) NOT NULL",
            "status"            => "BOOLEAN NOT NULL DEFAULT TRUE",
            "assigned_date"     => "DATETIME NOT NULL",
            "expired_date"      => "DATETIME NOT NULL",
            "encrypt_type"      => "VARCHAR(255) NOT NULL",
            "mcrypt_key"        => "TEXT NULL",
            "mcrypt_iv"         => "TEXT NULL",
            "auth_token"        => "TEXT NOT NULL",
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
        $tableColumns = parent::getColumnWithKey();
        if(count($tableColumns) != count($tablePropertyList)) {
            return null;
        }
        $dbTableProperty = new DbTableProperty(parent::$table);
        foreach($tablePropertyList as $key => $value){
            if(!array_key_exists($key, $tableColumns)) {
                return null;
            }
            $columnProperty = new DbColumnProperties($key, $value);
            $dbTableProperty->setColumProperty($columnProperty);
        }
        $dbTableProperty->setConstraintProperty(
            new DbColumnConstraintsProperties(DbColumnConstraintType::PRIMARY_KEY, "user_id")
        );
        return $dbTableProperty;
    }
}
?>