<?php
namespace RzSDK\SqlQueryBuilder;
?>
    <?php
use RzSDK\Utils\ArrayUtils;
use RzSDK\Log\DebugLog;
?>
<?php
trait SelectWhereSql {
    protected $where;
    protected $whereAnd;

    public function where($table, array $where, $isAnd = true): self {
        if(empty($where)) {
            return $this;
        }
        if(ArrayUtils::isMultidimensional($where)) {
            $this->where = $where;
            $this->whereAnd = $isAnd;
        } else {
            $table = trim($table);
            $this->whereAnd[] = $isAnd;
            if(empty($table)) {
                $this->where[] = $where;
            } else {
                $this->where[$table] = $where;
            }
        }
        //DebugLog::log($this->whereNew);
        return $this;
    }

    private function toWhereSql() {
        if(empty($this->where)) {
            return "";
        }
        /*DebugLog::log($this->whereNew);
        DebugLog::log($this->whereAndNew);*/
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
            $counter = 0;
            $andOr = "AND";
            foreach ($this->where as $key => $value) {
                if(is_int($key)) {
                    $key = "";
                }
                $andOr = $this->getAndOr($this->whereAnd, $counter);
                $column .= $this->getWhereColumnNew($value, $andOr, $key) . "{$andOr} ";
                $counter++;
            }
            $where = $where . $column;
            return trim(trim($where), $andOr);
        }
        $andOr = $this->getAndOr($this->whereAnd);
        foreach($this->where as $value) {
            $where .= "{$value} {$andOr} ";
        }
        return trim(trim($where), $andOr);
    }

    private function getWhereColumnNew(array $array, $andOr, $table = "") {
        if(!empty($table)) {
            $table = "{$table}.";
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
                $column .= "{$table}{$value} {$andOr} ";
            }
            return trim(trim($column), $andOr);
        }
    }

    private function getAndOr($andOr, $index = 0) {
        $retVal = "AND";
        if(is_array($andOr)) {
            if(!$andOr[$index]) {
                $retVal = "OR";
            }
        } else {
            if(!$andOr) {
                $retVal = "OR";
            }
        }
        return $retVal;
    }
}
?>