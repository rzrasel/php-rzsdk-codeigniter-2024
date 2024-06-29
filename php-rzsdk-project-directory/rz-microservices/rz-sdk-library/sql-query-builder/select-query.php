<?php
namespace RzSDK\SqlQueryBuilder;
?>
<?php
class SelectQuery {
    //
    protected $table;
    protected $columns;
    protected $where;
    protected $whereAnd;
    private $sqlQuery;
    //
    function setColumns($columns) {
        $this->columns = $columns;
        return $this;
    }

    public function from($table) {
        $this->table = $table;
        return $this;
    }

    public function where(array $where, $isAnd = true) {
        $this->where = $where;
        $this->whereAnd = $isAnd;
        return $this;
    }

    public function build() {
        $this->sqlQuery = "SELECT {$this->bindColumns()}"
            . " FROM {$this->bindTable()}"
            . " {$this->bindWhere()}"
            . "";
        $this->sqlQuery = preg_replace("/\s+/u", " ", $this->sqlQuery);
        return trim($this->sqlQuery) . ";";
    }

    private function bindColumns() {
        if(empty($this->columns)) {
            return "*";
        }
        //
        if($this->isMultidimensional($this->columns)) {
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
        if($this->isAssociative($array)) {
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
        if(is_array($this->table)) {
            $retVal = "";
            if($this->isAssociative($this->table)) {
                foreach($this->table as $key => $value) {
                    $retVal .= "{$key} AS {$value}, ";
                }
                return trim(trim($retVal), ",");
            } else {
                return trim(implode(", ", array_values($this->table)));
            }
        } else {
            return trim($this->table);
        }
    }

    private function bindWhere() {
        $where = "WHERE ";
        if($this->isMultidimensional($this->where)) {
            // Checking if array is multidimensional or not
            /*if($this->isAssociative($this->columns)) {
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
        if($this->isAssociative($array)) {
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

    public function isMultidimensional(array $array) {
        if(!is_array($array)) {
            return false;
        }
        if(count(array_filter($array,'is_array'))) {
            return true;
        }
        return false;
    }

    public function isAssociative(array $array) {
        if(!is_array($array)) {
            return false;
        }
        $keys = array_keys($array);
        /*if(array_is_list($this->columns)) {
            //
        }*/
        if($keys !== range(0, count($array) - 1)) {
            return true;
        }
        return false;
    }
}
?>