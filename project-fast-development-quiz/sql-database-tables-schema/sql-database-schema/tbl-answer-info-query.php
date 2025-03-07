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
class TblAnswerInfoQuery extends TblAnswerInfo {
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
            $this->question_id      => "BIGINT(20) NOT NULL",
            $this->answer_id        => "BIGINT(20) NOT NULL",
            $this->answer_bn        => "TEXT NOT NULL",
            $this->answer_en        => "TEXT NULL",
            $this->is_correct       => "BOOLEAN NOT NULL DEFAULT FALSE",
            $this->status           => "BOOLEAN NOT NULL DEFAULT TRUE",
            $this->modified_by      => "BIGINT(20) NOT NULL",
            $this->created_by       => "BIGINT(20) NOT NULL",
            $this->modified_date    => "DATETIME NOT NULL",
            $this->created_date     => "DATETIME NOT NULL",
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
            new DbColumnConstraintsProperties(DbColumnConstraintType::PRIMARY_KEY, $this->answer_id)
        );
        $dbTableProperty->setConstraintProperty(
            new DbColumnConstraintsProperties(DbColumnConstraintType::FOREIGN_KEY, $this->question_id, TblQuestionInfo::table(), TblQuestionInfo::$prefix, $this->question_id)
        );
        return $dbTableProperty;
    }
}
?>