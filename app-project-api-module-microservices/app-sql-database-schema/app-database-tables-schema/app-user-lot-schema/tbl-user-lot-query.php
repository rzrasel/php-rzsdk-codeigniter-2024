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
class TblUserLotQuery extends TblUserLot {
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
            $this->username         => "TEXT NOT NULL",
            $this->role_type        => "TEXT NOT NULL DEFAULT 'user' CHECK(role_type IN ('admin', 'user', 'moderator', 'guest'))",
            $this->account_expiry   => "TIMESTAMP NULL",
            $this->status           => "TEXT NOT NULL DEFAULT 'active' CHECK(status IN ('active', 'limited', 'blocked'))",
            $this->is_deleted       => "TEXT NOT NULL DEFAULT 'enabled' CHECK(is_deleted IN ('enabled', 'disabled', 'deleted', 'removed'))",
            $this->modified_date    => "TIMESTAMP DEFAULT CURRENT_TIMESTAMP",
            $this->created_date     => "TIMESTAMP DEFAULT CURRENT_TIMESTAMP",
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
            new DbColumnConstraintsProperties(DbColumnConstraintType::UNIQUE, $this->username, TblUserLot::table(), TblUserLot::$prefix, $this->username)
        );
        return $dbTableProperty;
    }
}
?>