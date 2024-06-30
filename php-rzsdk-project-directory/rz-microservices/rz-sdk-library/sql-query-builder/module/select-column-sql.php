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
    private $selectTable = [];

    public function select(string $table, array $column = array()): self {
        $this->isMultidimension = false;
        if(empty($column)) {
            return $this;
        }
        $this->selectTable[] = trim($table);
        $this->selectColumn[] = $column;
        return $this;
    }

    private function toSelectedColumn(): string {
        //DebugLog::log($this->selectColumn);
        if(empty($this->selectColumn) || count($this->selectColumn) == 0) {
            return "*";
        }
        return $this->bindSelectedColumns();
    }

    private function bindSelectedColumns() {
        //DebugLog::log($this->selectColumn);
        $column = "";
        $counter = 0;
        foreach($this->selectColumn as $array) {
            //DebugLog::log($array);
            $table = $this->selectTable[$counter];
            if(!empty($table)) {
                $table = "{$table}.";
            }
            if(ArrayUtils::isAssociative($array)) {
                // Associative array
                foreach($array as $key => $value) {
                    $column .= "{$table}{$key} AS {$value}, ";
                }
                //return trim(trim($column), ",");
            } else {
                // Sequential array
                //return trim(implode(", ", array_values($array)));
                //$column = "";
                foreach($array as $value) {
                    $column .= "{$table}{$value}, ";
                }
                //return trim(trim($column), ",");
            }
            $counter++;
        }
        return trim(trim($column), ",");
    }
}
?>