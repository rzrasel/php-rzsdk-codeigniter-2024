<?php
namespace RzSDK\SqlQueryBuilder;
?>
<?php
use RzSDK\Utils\ArrayUtils;
use RzSDK\SqlQueryBuilder\SelectColumnSql;
use RzSDK\SqlQueryBuilder\SelectOrderBySql;
use RzSDK\SqlQueryBuilder\SelectLimitSql;
use RzSDK\SqlQueryBuilder\SelectOffsetSql;
use RzSDK\Log\DebugLog;
?>
<?php
class SelectQuery {
    //
    use SelectColumnSql;
    use SelectOrderBySql;
    use SelectLimitSql;
    use SelectOffsetSql;
    //
    protected $fromTable;
    protected $fromTableAlias;
    protected $columns;
    protected $joinTables;
    protected $joinColums;
    protected $where;
    protected $whereAnd;
    private $sqlQuery;
    //
    function setColumns($columns) {
        $this->columns = $columns;
        return $this;
    }

    public function from($table, $alias = "") {
        $this->fromTable = $table;
        $this->fromTableAlias = $alias;
        return $this;
    }

    public function innerJoin(array $joinTables, array $joinColums) {
        $this->joinTables = $joinTables;
        $this->joinColums = $joinColums;
        return $this;
    }

    public function where(array $where, $isAnd = true) {
        $this->where = $where;
        $this->whereAnd = $isAnd;
        return $this;
    }

    public function build() {
        $this->sqlQuery = "SELECT {$this->bindColumnsOld()}"
            . " FROM {$this->bindTable()}"
            . " {$this->bindInnerJoin()}"
            . " {$this->bindWhere()}"
            . " {$this->toOrderBySql()}"
            . " {$this->toLimitSql()}"
            . " {$this->toOffsetSql()}"
            . " ";
        $this->sqlQuery = preg_replace("/\s+/u", " ", $this->sqlQuery);
        return trim($this->sqlQuery) . ";";
    }

    public function buildNew() {
        $this->sqlQuery = "SELECT {$this->toSelectedColumn()}"
            . " FROM {$this->bindTable()}"
            . " ";
        $this->sqlQuery = preg_replace("/\s+/u", " ", $this->sqlQuery);
        return trim($this->sqlQuery) . ";";
    }

    private function bindColumnsOld() {
        if(empty($this->columns)) {
            return "*";
        }
        //
        if(ArrayUtils::isMultidimensional($this->columns)) {
            // Checking if array is multidimensional or not
            $column = "";
            foreach ($this->columns as $key => $value) {
                if(is_int($key)) {
                    $key = "";
                }
                $column .= $this->getSelectColumn($value, $key) . ", ";
            }
            return trim(trim($column), ",");
        }
        return $this->getSelectColumn($this->columns);
    }

    private function getSelectColumn(array $array, $table = "") {
        if(!empty($table)) {
            $table = "{$table}.";
        }
        if(ArrayUtils::isAssociative($array)) {
            // Associative array
            $column = "";
            foreach($array as $key => $value) {
                $column .= "{$table}{$key} AS {$value}, ";
            }
            return trim(trim($column), ",");
        } else {
            // Sequential array
            //return trim(implode(", ", array_values($array)));
            $column = "";
            foreach($array as $value) {
                $column .= "{$table}{$value}, ";
            }
            return trim(trim($column), ",");
        }
    }

    private function bindTable() {
        if(is_array($this->fromTable)) {
            $retVal = "";
            if(ArrayUtils::isAssociative($this->fromTable)) {
                foreach($this->fromTable as $key => $value) {
                    $retVal .= "{$key} AS {$value}, ";
                }
                return trim(trim($retVal), ",");
            } else {
                return trim(implode(", ", array_values($this->fromTable)));
            }
        } else {
            if(!empty($this->fromTableAlias)) {
                return trim($this->fromTable) . " AS " . trim($this->fromTableAlias);
            }
            return trim($this->fromTable);
        }
    }

    private function bindInnerJoin() {
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

    private function bindWhere() {
        $where = "WHERE ";
        if(ArrayUtils::isMultidimensional($this->where)) {
            // Checking if array is multidimensional or not
            /*if(ArrayUtils::isAssociative($this->columns)) {
                //
            } else {
                return trim(implode("AND ", array_values($this->columns)));
            }
            return trim(trim($where), "AND");*/
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
    }

    private function getWhereColumn(array $array, $isAnd, $table = "") {
        if(!empty($table)) {
            $table = "{$table}.";
        }
        $and = "AND";
        if(!$isAnd) {
            $and = "OR";
        }
        if(ArrayUtils::isAssociative($array)) {
            // Associative array
            /*$column = "";
            foreach($array as $key => $value) {
                $column .= "{$table}{$key} AS {$value}, ";
            }
            return trim(trim($column), ",");*/
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
    }
}
?>