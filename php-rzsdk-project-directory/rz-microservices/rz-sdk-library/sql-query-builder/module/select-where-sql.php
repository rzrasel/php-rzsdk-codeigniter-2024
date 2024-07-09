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
                /*$firstCharacter = trim($value);
                $firstCharacter = substr($firstCharacter, 0, 1);
                $pattern = "/^[=><]$/i";
                if(preg_match($pattern, $firstCharacter, $matches)) {
                    //DebugLog::log($matches[0]);
                    $operator = $matches[0];
                    $value = ltrim(substr($value, 1));
                }*/
                //DebugLog::log($firstCharacter);
                $sqlOperator = $this->getSqlOperator($value);
                //DebugLog::log($sqlOperator);
                if(!empty($sqlOperator[0])) {
                    $operator = preg_replace("/\s+/", "", $sqlOperator[0]);
                    $value = $sqlOperator[1];
                    $trimValue = trim($value);
                    if(is_bool($trimValue)) {
                        if($value) {
                            $value = "TRUE";
                        } else {
                            $value = "FALSE";
                        }
                    } else if(strtolower($trimValue) == "true") {
                        $value = "TRUE";
                    } else if(strtolower($trimValue) == "false") {
                        $value = "FALSE";
                    } else if(is_numeric($trimValue)) {
                        $value = $trimValue;
                    } else {
                        $value = "'{$value}'";
                    }
                } else {
                    if(is_bool($value)) {
                        if($value) {
                            $value = "TRUE";
                        } else {
                            $value = "FALSE";
                        }
                    } else if(is_int($value) || is_float($value) || is_numeric($value)) {
                        $value = $value;
                    } else {
                        $value = "'{$value}'";
                    }
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

    private function getSqlOperator(string $string) {
        if(empty($string)) {
            return array(
                null,
                $string,
            );
        }
        $regexPattern = $this->getRegExrPattern();
        $regexPattern = $regexPattern[0];
        $operator = $this->getRegExrPregMatch($regexPattern, $string);
        $sqlStr = $this->removeRegExrPatterFromString("{" . $operator . "}", $string);
        return array(
            $operator,
            $sqlStr,
        );
    }

    private function getRegExrPattern() {
        $pattern = array();
        $pattern[] = "\{(.*?)\}"; //{}
        $pattern[] = "\[\{(.*?)\}\]"; //[{}]
        $pattern[] = "\[\((.*?)\)\]"; //[()]
        $pattern[] = "\{\((.*?)\)\}"; //{()}
        return $pattern;
    }

    private function getRegExrPregMatch(string $pattern, string $string) {
        if(empty($pattern) || empty($string)) {
            return null;
        }
        //DebugLog::log($pattern);
        //DebugLog::log($string);
        $regexPattern = "/^\s+{$pattern}$/si";
        //$regexPattern = "/^\s+{$pattern}/si";
        $regexPattern = "/^[\s+]?+{$pattern}/si";
        //DebugLog::log($regexPattern);
        if(preg_match($regexPattern, $string, $match)) {
            //DebugLog::log($match);
            return $match[1];
        }
        return null;
    }

    private function getRegExrPregMatchAll(string $pattern, string $string) {
        if(empty($pattern) || empty($string)) {
            return null;
        }
        $regexPattern = "/^[\s+]?+{$pattern}/si";
        if(preg_match_all($regexPattern, $string, $matches)) {
            return $matches[1][0];
        }
        return null;
    }

    private function removeRegExrPatterFromString(string $pattern, string $string) {
        if(empty($pattern) || empty($string)) {
            return $string;
        }
        //DebugLog::log($pattern);
        //DebugLog::log($string);
        $strPos = strpos($string, $pattern) + strlen($pattern);
        return substr($string, $strPos, strlen($string));
    }
}
?>