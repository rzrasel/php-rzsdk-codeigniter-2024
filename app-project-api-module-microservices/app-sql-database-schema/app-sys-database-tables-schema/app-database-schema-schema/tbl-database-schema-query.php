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
class TblDatabaseSchemaQuery extends TblDatabaseSchema {
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
            $this->id               => "BIGINT(20) NOT NULL",
            //$this->unique_name      => "TEXT NOT NULL",
            $this->schema_name      => "TEXT NOT NULL",
            $this->schema_version   => "TEXT NULL",
            $this->table_prefix     => "TEXT NULL",
            $this->database_comment => "TEXT NULL",
            "modified_date"         => "DATETIME NOT NULL",
            "created_date"          => "DATETIME NOT NULL",
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
            new DbColumnConstraintsProperties(DbColumnConstraintType::UNIQUE, $this->unique_name, "", "", "")
        );*/
        $dbTableProperty->setConstraintProperty(
            new DbColumnConstraintsProperties(DbColumnConstraintType::UNIQUE, $this->schema_name, TblDatabaseSchema::table(), TblDatabaseSchema::$prefix, $this->schema_name)
        );
        /*$dbTableProperty->setConstraintProperty(
            new DbColumnConstraintsProperties(DbColumnConstraintType::UNIQUE, $this->name, "", "", "")
        );*/
        return $dbTableProperty;
    }
}
?>