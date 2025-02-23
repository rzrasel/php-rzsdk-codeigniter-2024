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
class TblUserSysRoleQuery extends TblUserSysRole {
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
            $this->id               => "VARCHAR(36) NOT NULL",
            $this->name             => "VARCHAR(255) NOT NULL",
            $this->description      => "TEXT NULL",
            $this->user_type        => "TEXT NOT NULL DEFAULT 'registered' CHECK(user_type IN ('open', 'guest', 'registered', 'admin')), -- User type for this role",
            $this->priority         => "INT NOT NULL DEFAULT 0, -- Role hierarchy (higher value = higher priority)",
            $this->is_default       => "BOOLEAN NOT NULL DEFAULT FALSE, -- Is this a default role?",
            $this->status           => "TEXT NOT NULL DEFAULT 'active' CHECK(status IN ('active', 'blocked', 'inactive', 'deleted'))",
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
        /*$dbTableProperty->setConstraintProperty(
            new DbColumnConstraintsProperties(DbColumnConstraintType::FOREIGN_KEY, $this->id, TblUserSysRole::table(), TblUserSysRole::$prefix, $this->id)
        );*/
        return $dbTableProperty;
    }
}
?>