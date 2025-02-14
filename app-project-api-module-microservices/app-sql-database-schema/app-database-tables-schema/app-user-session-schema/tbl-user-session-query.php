<?php
namespace RzSDK\Database\Schema;
?>
<?php
use RzSDK\Database\DbType;
use RzSDK\Database\DbTableProperty;
use RzSDK\Database\DbColumnProperties;
use RzSDK\Database\DbColumnConstraintsProperties;
use RzSDK\Database\DbColumnConstraintType;
use RzSDK\Database\DbSqlQueryGenerator;
use RzSDK\Log\DebugLog;
?>
<?php
class TblUserSessionQuery extends TblUserSession {
    private DbType $dbType;

    public function __construct(DbType $dbType) {
        $this->dbType = $dbType;
        //$this->execute(DbType::SQLITE);
    }

    public function dropQuery() {
        $table = parent::tableWithPrefix();
        return "DROP TABLE IF EXISTS " . $table . ";";
    }

    public function deleteQuery() {
        $table = parent::tableWithPrefix();
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
            $this->user_id          => "VARCHAR(36) NOT NULL",
            $this->id               => "VARCHAR(36) NOT NULL",
            $this->hash_type        => "TEXT NOT NULL DEFAULT 'password_hash' CHECK(hash_type IN ('password_hash', 'SHA256', 'bcrypt', 'argon2'))",
            $this->session_salt     => "TEXT NOT NULL",
            $this->session_id       => "TEXT NOT NULL",
            $this->session_duration => "INT(4) DEFAULT 0",
            $this->expires_at       => "TIMESTAMP NOT NULL",
            $this->refresh_token    => "TEXT NULL",
            $this->ip_address       => "VARCHAR(255) NULL",
            $this->user_agent       => "VARCHAR(255) NULL",
            $this->device           => "VARCHAR(255) NULL",
            $this->browser          => "VARCHAR(255) NULL",
            $this->os               => "VARCHAR(255) NULL",
            $this->login_time       => "TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP",
            $this->logout_time      => "TIMESTAMP NULL",
            $this->remember_me      => "BOOLEAN NOT NULL DEFAULT FALSE",
            $this->status           => "TEXT NOT NULL DEFAULT 'active' CHECK(status IN ('active', 'inactive', 'blocked', 'deleted', 'removed'))",
            $this->modified_date    => "TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP",
            $this->created_date     => "TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP",
            $this->modified_by      => "VARCHAR(36) NOT NULL",
            $this->created_by       => "VARCHAR(36) NOT NULL",
        );
        $tableColumns = parent::getColumnWithKey();
        if(count($tableColumns) != count($tablePropertyList)) {
            return null;
        }
        $dbTableProperty = new DbTableProperty(parent::$table, parent::$prefix);
        foreach($tablePropertyList as $key => $value){
            if(!array_key_exists($key, $tableColumns)) {
                return null;
            }
            $columnProperty = new DbColumnProperties($key, $value);
            $dbTableProperty->setColumProperty($columnProperty);
        }
        $dbTableProperty->setConstraintProperty(
            new DbColumnConstraintsProperties(DbColumnConstraintType::PRIMARY_KEY, $this->id)
        );
        $dbTableProperty->setConstraintProperty(
            new DbColumnConstraintsProperties(DbColumnConstraintType::UNIQUE, $this->session_id, TblUserSession::table(), TblUserSession::$prefix, $this->session_id)
        );
        $dbTableProperty->setConstraintProperty(
            new DbColumnConstraintsProperties(DbColumnConstraintType::FOREIGN_KEY, $this->user_id, TblUserData::table(), TblUserData::$prefix, $this->id)
        );
        return $dbTableProperty;
    }
}
?>