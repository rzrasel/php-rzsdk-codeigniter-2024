<?php
namespace RzSDK\SqlQueryBuilder;
?>
<?php
use RzSDK\Utils\ArrayUtils;
use RzSDK\Log\DebugLog;
?>
<?php
trait SelectJoinSql {
    protected $joinDataList;
    private $joinSqlQuery;
    //
    private $leftTableKey = "left_table";
    private $rightTableKey = "right_table";
    private $rightColumnKey = "left_column";
    private $leftColumnKey = "right_column";
    //

    public function join($leftTable, $rightTable, string $leftColumn, string $rightColumn) {
        $this->setJoinData("JOIN", $leftTable, $rightTable, $leftColumn, $rightColumn);
        return $this;
    }

    public function innerJoin($leftTable, $rightTable, string $leftColumn, string $rightColumn) {
        $this->setJoinData("INNER JOIN", $leftTable, $rightTable, $leftColumn, $rightColumn);
        return $this;
    }

    public function leftJoin($leftTable, $rightTable, string $leftColumn, string $rightColumn) {
        $this->setJoinData("LEFT JOIN", $leftTable, $rightTable, $leftColumn, $rightColumn);
        return $this;
    }

    public function rightJoin($leftTable, $rightTable, string $leftColumn, string $rightColumn) {
        $this->setJoinData("RIGHT JOIN", $leftTable, $rightTable, $leftColumn, $rightColumn);
        return $this;
    }

    public function fullJoin($leftTable, $rightTable, string $leftColumn, string $rightColumn) {
        $this->setJoinData("FULL JOIN", $leftTable, $rightTable, $leftColumn, $rightColumn);
        return $this;
    }

    private function setJoinData($joinType, $leftTable, $rightTable, string $leftColumn, string $rightColumn) {
        $this->joinDataList[] = array(
            $joinType => array(
                $this->leftTableKey     => $leftTable,
                $this->rightTableKey    => $rightTable,
                $this->leftColumnKey    => $leftColumn,
                $this->rightColumnKey   => $rightColumn,
            )
        );
    }

    private function toInnerJoinStatement() {
        //DebugLog::log($this->innerJoinLeftTable);
        //DebugLog::log($this->innerJoinRightTable);
        //DebugLog::log($this->joinDataList);
        if(empty($this->joinDataList)) {
            return "";
        }
        $this->joinSqlQuery = "";
        foreach($this->joinDataList as $joinProperties) {
            foreach($joinProperties as $joinKey => $joinValues) {
                $joinType = $joinKey;
                //DebugLog::log($joinValues);
                $leftJoinTable = $joinValues[$this->leftTableKey];
                $rightJoinTable = $joinValues[$this->rightTableKey];
                $leftJoinColumn = $joinValues[$this->leftColumnKey];
                $rightJoinColumn = $joinValues[$this->rightColumnKey];
                //
                $leftTable = $this->getInnerJoinTable($leftJoinTable);
                $rightTable = $this->getInnerJoinTable($rightJoinTable);
                $this->joinSqlQuery .= " {$joinType}"
                    . " {$rightTable[1]}"
                    . " ON"
                    . " {$leftTable[0]}.{$leftJoinColumn}"
                    . " ="
                    . " {$rightTable[0]}.{$rightJoinColumn}"
                    . " ";
            }
        }
        //DebugLog::log($retVal);
        return trim($this->joinSqlQuery);
    }

    private function getInnerJoinTable($tables) {
        // Index 0 for get table name, index 1 for get join: table as table alias
        $retVai = array();
        if(is_array($tables)) {
            foreach($tables as $key => $value) {
                $sql = $value;
                if(!is_int($key)) {
                    $sql = "{$key} AS {$value}";
                }
                $retVai = array($value, $sql);
            }
        } else {
            $table = trim($tables);
            $retVai = array($table, $table);
        }
        return $retVai;
    }
}
?>
<?php
//https://learnsql.com/blog/how-to-use-aliases-with-sql-join/
//https://librarycarpentry.org/lc-sql/06-joins-aliases.html
//https://www.geeksforgeeks.org/sql-join-set-1-inner-left-right-and-full-joins/
?>
