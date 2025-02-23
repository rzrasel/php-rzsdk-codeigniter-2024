<?php
namespace RzSDK\SqlQueryBuilder;
?>
<?php
use RzSDK\Utils\ArrayUtils;
use RzSDK\Log\DebugLog;
?>
<?php
trait SelectColumnSql {
    private $selectColumns = [];
    private $rawSelectSql;
    private $selectTable;

    //public function select(string $table = "", array $column = array()): self {}
    public function __construct(string $table = "", array $column = array()) {
        $this->rawSelectSql = $table;
        if(empty($column)) {
            return $this;
        }
        if(ArrayUtils::isMultidimensional($column)) {
            $this->selectColumns = $column;
            $this->selectTable = trim($table);
        } else {
            $table = trim($table);
            if(empty($table)) {
                $this->selectColumns[] = $column;
            } else {
                $this->selectColumns[$table] = $column;
            }
        }
        return $this;
    }

    private function toSelectStatement(): string {
        //DebugLog::log($this->selectColumn);
        if(empty($this->selectColumns) || count($this->selectColumns) == 0) {
            if(!empty($this->rawSelectSql)) {
                return $this->rawSelectSql;
            }
            return "*";
        }
        return $this->bindSelectedColumns();
    }

    private function bindSelectedColumns() {
        /*if(empty($this->selectTable)) {
            return "";
        }*/
        if(ArrayUtils::isMultidimensional($this->selectColumns)) {
            // Checking if array is multidimensional or not
            $column = "";
            //$counter = 0;
            foreach($this->selectColumns as $key => $value) {
                $table = trim($key);
                if(is_int($key)) {
                    //$key = "";
                    $table = $this->selectTable;
                }
                $column .= $this->getSelectColumn($table, $value) . ", ";
                //$counter++;
            }
            return trim(trim($column), ",");
        }
        $table = $this->selectTable;
        return $this->getSelectColumn($table, $this->selectColumn);
    }

    private function getSelectColumn($table, array $array) {
        if(!empty($table)) {
            $table = "{$table}.";
        }
        if(ArrayUtils::isAssociative($array)) {
            // Associative array
            $column = "";
            foreach($array as $key => $value) {
                $key = trim($key);
                $value = trim($value);
                $column .= "{$table}{$key} AS {$value}, ";
            }
            return trim(trim($column), ",");
        } else {
            // Sequential array
            //return trim(implode(", ", array_values($array)));
            $column = "";
            foreach($array as $value) {
                $value = trim($value);
                $column .= "{$table}{$value}, ";
            }
            return trim(trim($column), ",");
        }
    }
}
?>