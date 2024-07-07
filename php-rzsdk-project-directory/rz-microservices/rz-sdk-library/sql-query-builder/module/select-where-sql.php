<?php
namespace RzSDK\SqlQueryBuilder;
?>
<?php
use RzSDK\Utils\ArrayUtils;
use RzSDK\Log\DebugLog;
?>
<?php
trait SelectWhereSql {
    protected $whereColumns;
    protected $whereTable;
    protected $whereAnd;

    public function where($table = "", array $where = array(), $isAnd = true): self {
        if(empty($where)) {
            return $this;
        }
        if(ArrayUtils::isMultidimensional($where)) {
            //$table = trim($table);
            $this->whereColumns = $where;
            $this->whereTable = trim($table);
            $this->whereAnd = $isAnd;
        } else {
            $table = trim($table);
            $this->whereAnd[] = $isAnd;
            if(empty($table)) {
                $this->whereColumns[] = $where;
            } else {
                if(!empty($this->whereColumns)) {
                    if (array_key_exists($table, $this->whereColumns)) {
                        /*$this->whereColumns[$table] = array();
                        $combineWhereColumns = array();
                        foreach($this->whereColumns as $key => $value) {
                            $combineWhereColumns[$key] = $value;
                        }*/
                        //$this->whereColumns[$table][] = array_merge($this->whereColumns[$table], $where);
                        /*$arrayValue = array_values($where);
                        $this->whereColumns[$table][] = $arrayValue;*/
                    } else {
                        $this->whereColumns[$table] = $where;
                    }
                } else {
                    $this->whereColumns[$table] = $where;
                }
                //$this->whereColumns[$table] = $where;
            }
        }
        //DebugLog::log($this->whereNew);
        return $this;
    }

    private function toWhereStatement() {
        if(empty($this->whereColumns)) {
            return "";
        }
        //DebugLog::log($this->whereColumns);
        //DebugLog::log($this->whereAndNew);
        $where = "WHERE ";
        if(ArrayUtils::isMultidimensional($this->whereColumns)) {
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
            foreach($this->whereColumns as $key => $value) {
                $table = trim($key);
                if(is_int($key)) {
                    //$key = "";
                    //$table = $this->selectTable;
                    $table = $this->whereTable;
                }
                $andOr = $this->getAndOr($this->whereAnd, $counter);
                $column .= $this->getWhereColumn($table, $value, $andOr) . "{$andOr} ";
                $counter++;
            }
            $where = $where . $column;
            return trim(trim($where), $andOr);
        }
        $table = $this->selectTable;
        if(!empty($table)) {
            $table = "{$table}.";
        }
        $andOr = $this->getAndOr($this->whereAnd);
        foreach($this->where as $key => $value) {
            $key = trim($key);
            $value = trim($value);
            if(!is_string($key) && !empty($key)) {
                //$key = "";
                $table = "{$key}.";
            }
            $where .= "{$table}{$value} {$andOr} ";
        }
        return trim(trim($where), $andOr);
    }

    private function getWhereColumn($table, array $array, $andOr) {
        if(!empty($table)) {
            $table = "{$table}.";
        }
        if(ArrayUtils::isAssociative($array)) {
            // Associative array
            $column = "";
            foreach($array as $key => $value) {
                $operator = "=";
                $firstCharacter = trim($value);
                $firstCharacter = substr($firstCharacter, 0, 1);
                $pattern = "/^[=><]$/i";
                if(preg_match($pattern, $firstCharacter, $matches)) {
                    //DebugLog::log($matches[0]);
                    $operator = $matches[0];
                    $value = ltrim(substr($value, 1));
                }
                //DebugLog::log($firstCharacter);
                if(is_bool($value)) {
                    $value = "TRUE";
                } else if(is_int($value)) {
                    $value = $value;
                } else {
                    $value = "'{$value}'";
                }
                $column .= " {$table}{$key} {$operator} {$value} {$andOr} ";
            }
            //return trim($column, "{$andOr}");
            return trim(trim($column), $andOr);
            //return "ERROR, associative array not working";
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