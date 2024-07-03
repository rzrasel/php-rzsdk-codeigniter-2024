<?php
namespace RzSDK\SqlQueryBuilder;
?>
<?php
use RzSDK\Utils\ArrayUtils;
use RzSDK\Log\DebugLog;
?>
<?php
trait SelectMultidimensionColumnSql {
    private $selectMultiColumn;

    public function selectMultidimension(array $columns = array()): self {
        $this->isMultidimension = true;
        $this->selectMultiColumn = $columns;
        DebugLog::log($this->selectMultiColumn);
        return $this;
    }

    private function toSelectedMultiColumn() {
        if(!$this->isMultidimension) {
            return "";
        }
        if(empty($this->selectMultiColumn)) {
            return "*";
        }
        //
        if(ArrayUtils::isMultidimensional($this->selectMultiColumn)) {
            // Checking if array is multidimensional or not
            $column = "";
            foreach ($this->selectMultiColumn as $key => $value) {
                if(is_int($key)) {
                    $key = "";
                }
                $column .= $this->getSelectColumn($value, $key) . ", ";
            }
            return trim(trim($column), ",");
        }
        return $this->getSelectColumn($this->selectMultiColumn);
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

    /*private function bindSelectedMultiColumns(): string {
        return "";
    }*/
}
?>