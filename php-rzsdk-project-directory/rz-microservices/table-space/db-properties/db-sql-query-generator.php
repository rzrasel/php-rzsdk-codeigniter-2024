<?php
namespace RzSDK\DatabaseSpace;
?>
<?php

use RzSDK\DatabaseSpace\DbColumnConstraintsProperties;
use RzSDK\DatabaseSpace\DbColumnConstraintType;
use RzSDK\DatabaseSpace\DbColumnProperties;
use RzSDK\DatabaseSpace\DbTableProperty;
use RzSDK\Log\DebugLog;
?>
<?php
class DbSqlQueryGenerator {
    private DbTableProperty $tableProperty;

    public function __construct(DbTableProperty $tableProperty) {
        $this->tableProperty = $tableProperty;
    }

    public function build() {
        $tableProperty = $this->tableProperty;
        //DebugLog::log($tableProperty);
        $table = $tableProperty->table;
        $dbColumnProperties = $tableProperty->columnProperties;
        $dbConstraintsProperties = $tableProperty->constraintsProperties;
        //
        $maxStrPadSpace = $this->getStrPad($dbColumnProperties);
        $sqlQuery = "";
        $sqlQuery .= "CREATE TABLE IF NOT EXISTS " . $table . " (" . "";
        $sqlQuery .= "<br />";
        if(!empty($dbColumnProperties)) {
            foreach($dbColumnProperties as $value) {
                $columnProperty = $tableProperty->getCastColumProperty($value);
                $column = $columnProperty->column;
                $property = $columnProperty->property;
                $sqlQuery .= "    "
                    //. $column . " "
                    . str_pad($column, $maxStrPadSpace, " ")
                    . $property
                    . ",";
                $sqlQuery .= "<br />";
            }
        }
        //$sqlQuery = trim($sqlQuery, ",<br />");
        if(!empty($dbConstraintsProperties)) {
            foreach($dbConstraintsProperties as $value) {
                $constraintProperty = $tableProperty->getCastConstraintProperty($value);
                $sqlQuery .= "    "
                    . $this->getConstraintProperty($table, $constraintProperty);
                $sqlQuery .= "<br />";
            }
        }
        $sqlQuery = rtrim($sqlQuery, "<br />");
        $sqlQuery = rtrim($sqlQuery, ",<br />");
        $sqlQuery = rtrim($sqlQuery, ",");
        $sqlQuery .= "<br />";
        $sqlQuery .= ");";
        //DebugLog::log($sqlQuery);
        return $sqlQuery;
    }

    private function getConstraintProperty($table, DbColumnConstraintsProperties $constraintProperty) {
        $sqlQuery = "";
        $constraintKey = "{$table}_{$constraintProperty->primaryColumns}";
        if($constraintProperty->constraintType == DbColumnConstraintType::FOREIGN_KEY) {
            $sqlQuery = "CONSTRAINT"
                . " fk_{$constraintKey}"
                . " FOREIGN KEY"
                . " ({$constraintProperty->primaryColumns})"
                . " REFERENCES"
                . " {$constraintProperty->referenceTable}"
                . "({$constraintProperty->referenceColumn})"
                . ",";
        } else if($constraintProperty->constraintType == DbColumnConstraintType::PRIMARY_KEY) {
            $primaryColumn = $constraintProperty->primaryColumns;
            if(is_array($primaryColumn)) {
                $primaryColumn = implode(", ", $primaryColumn);
            }
            $sqlQuery = "CONSTRAINT"
                . " pk_{$constraintKey}"
                . " PRIMARY KEY"
                . " ({$primaryColumn})"
                . ",";
        } else if($constraintProperty->constraintType == DbColumnConstraintType::UNIQUE) {
            $primaryColumn = $constraintProperty->primaryColumns;
            if(is_array($primaryColumn)) {
                $primaryColumn = implode(", ", $primaryColumn);
            }
            $sqlQuery = "CONSTRAINT"
                . " uk_{$constraintKey}"
                . " UNIQUE"
                . " ({$primaryColumn})"
                . ",";
        }
        return $sqlQuery;
    }

    private function getStrPad($dbColumnProperties, $padLength = 4) {
        $keyLength = -1;
        if(empty($dbColumnProperties)) {
            return $keyLength;
        }
        foreach($dbColumnProperties as $value) {
            if(strlen($value->column) > $keyLength) {
                $keyLength = strlen($value->column);
            }
        }
        return $keyLength + $padLength;
    }
}
?>