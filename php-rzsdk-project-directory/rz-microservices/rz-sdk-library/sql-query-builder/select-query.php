<?php
namespace RzSDK\SqlQueryBuilder;
?>
<?php
use RzSDK\Utils\ArrayUtils;
use RzSDK\SqlQueryBuilder\SelectColumnSql;
use RzSDK\SqlQueryBuilder\SelectMultidimensionColumnSql;
use RzSDK\SqlQueryBuilder\SelectFromTableSql;
use RzSDK\SqlQueryBuilder\SelectWhereSql;
use RzSDK\SqlQueryBuilder\SelectOrderBySql;
use RzSDK\SqlQueryBuilder\SelectLimitSql;
use RzSDK\SqlQueryBuilder\SelectOffsetSql;
use RzSDK\Log\DebugLog;
?>
<?php
class SelectQuery {
    //
    use SelectColumnSql;
    use SelectMultidimensionColumnSql;
    use SelectFromTableSql;
    use SelectWhereSql;
    use SelectOrderBySql;
    use SelectLimitSql;
    use SelectOffsetSql;
    //
    protected $isMultidimension = true;
    //protected $columns;
    protected $joinTables;
    protected $joinColums;
    /*protected $where;
    protected $whereAnd;*/
    private $sqlQuery;
    //
    /*function setColumns($columns) {
        $this->columns = $columns;
        return $this;
    }*/

    public function innerJoin(array $joinTables, array $joinColums) {
        $this->joinTables = $joinTables;
        $this->joinColums = $joinColums;
        return $this;
    }

    /*public function where($table, array $where, $isAnd) {
        $this->where = $where;
        $this->whereAnd = $isAnd;
        return $this;
    }*/

    public function build() {
        $this->sqlQuery = "SELECT"
            . " {$this->toSelectedMultiColumn()}"
            . " {$this->toSelectedColumn()}"
            . " FROM {$this->toFromTableSql()}"
            . " {$this->bindInnerJoin()}"
            . " {$this->toWhereSql()}"
            . " {$this->toOrderBySql()}"
            . " {$this->toLimitSql()}"
            . " {$this->toOffsetSql()}"
            . " ";
        $this->sqlQuery = preg_replace("/\s+/u", " ", $this->sqlQuery);
        return trim($this->sqlQuery) . ";";
    }

    private function bindInnerJoin() {
        if(empty($this->joinTables)) {
            return "";
        }
        $retVal = "INNER JOIN ";
        if(ArrayUtils::isAssociative($this->joinTables)) {
            $table = array();
            $alias = array();
            foreach ($this->joinTables as $key => $value) {
                $table[] = $key;
                $alias[] = $value;
            }
            $retVal .= "{$table[1]} AS {$alias[1]} "
                . " ON"
                . " {$alias[0]}.{$this->joinColums[0]} "
                . " ="
                . " {$alias[1]}.{$this->joinColums[1]} "
                . "";
        } else {
            $retVal .= "{$this->joinTables[0]} "
                . " ON"
                . " {$this->joinTables[0]}.{$this->joinColums[0]} "
                . " ="
                . " {$this->joinTables[1]}.{$this->joinColums[1]} "
                . "";
        }
        return $retVal;
    }

    /*private function bindWhere() {
        if(empty($this->where)) {
            return "";
        }
        $where = "WHERE ";
        if(ArrayUtils::isMultidimensional($this->where)) {
            // Checking if array is multidimensional or not
            /-*if(ArrayUtils::isAssociative($this->columns)) {
                //
            } else {
                return trim(implode("AND ", array_values($this->columns)));
            }
            return trim(trim($where), "AND");*-/
            $column = "";
            foreach ($this->where as $key => $value) {
                if(is_int($key)) {
                    $key = "";
                }
                $column .= $this->getWhereColumn($value, $this->whereAnd, $key) . "AND ";
            }
            $where = $where . $column;
            return trim(trim($where), "AND");
        }
        foreach($this->where as $value) {
            $where .= "{$value} AND ";
        }
        return trim(trim($where), "AND");
    }*/

    /*private function getWhereColumn(array $array, $isAnd, $table = "") {
        if(!empty($table)) {
            $table = "{$table}.";
        }
        $and = "AND";
        if(!$isAnd) {
            $and = "OR";
        }
        if(ArrayUtils::isAssociative($array)) {
            // Associative array
            /-*$column = "";
            foreach($array as $key => $value) {
                $column .= "{$table}{$key} AS {$value}, ";
            }
            return trim(trim($column), ",");*-/
            return "ERROR, associative array not working";
        } else {
            // Sequential array
            //return trim(implode(", ", array_values($array)));
            $column = "";
            foreach($array as $value) {
                $column .= "{$table}{$value} {$and} ";
            }
            return trim(trim($column), $and);
        }
    }*/
}
?>