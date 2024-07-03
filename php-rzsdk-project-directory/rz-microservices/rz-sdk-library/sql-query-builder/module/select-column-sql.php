<?php
namespace RzSDK\SqlQueryBuilder;
?>
<?php
use RzSDK\Utils\ArrayUtils;
use RzSDK\Log\DebugLog;
?>
<?php
trait SelectColumnSql {
    private $selectColumn = [];
    private $selectTable;

    public function select(string $table, array $column = array()): self {
        if(empty($column)) {
            return $this;
        }
        if(ArrayUtils::isMultidimensional($column)) {
            $this->selectTable = trim($table);
            $this->selectColumn = $column;
        } else {
            $table = trim($table);
            if(empty($table)) {
                $this->selectColumn[] = $column;
            } else {
                $this->selectColumn[$table] = $column;
            }
        }
        return $this;
    }

    private function toSelectStatement(): string {
        //DebugLog::log($this->selectColumn);
        if(empty($this->selectColumn) || count($this->selectColumn) == 0) {
            return "*";
        }
        return $this->bindSelectedColumns();
    }

    private function bindSelectedColumns() {
        if(ArrayUtils::isMultidimensional($this->selectColumn)) {
            // Checking if array is multidimensional or not
            $column = "";
            //$counter = 0;
            foreach ($this->selectColumn as $key => $value) {
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