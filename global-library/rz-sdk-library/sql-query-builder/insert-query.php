<?php
namespace RzSDK\SqlQueryBuilder;
?>
<?php
use InvalidArgumentException;
?>
<?php
class InsertQuery {
    //
    protected $table;
    protected $tableData;
    private $sqlQuery;
    //
    //public function __construct() {}

    //function setTable($table) {
    function __construct($table) {
        $this->table = $table;
        return $this;
    }

    public function values(array $column) {
        if (array_values($column) == $column)
            throw new InvalidArgumentException("Value should be an associative array");
        $this->tableData = $column;
        return $this;
    }

    public function build() {
        $this->sqlQuery = "INSERT INTO {$this->table} "
        . "({$this->bindColumns()}) "
        . "VALUES ({$this->bindValues()})"
        . ";";
        return $this->sqlQuery;
    }

    private function bindColumns() {
        return trim(
            implode(", ", array_keys($this->tableData))
        );
    }

    private function bindValues() {
        $values = "";
        foreach(array_values($this->tableData) as $item) {
            if(is_bool($item)) {
                if($item) {
                    $values .= "TRUE, ";
                } else {
                    $values .= "FALSE, ";
                }
            } else if(empty($item)) {
                if(is_int($item) || is_numeric($item)) {
                    $values .= "'" . $item . "', ";
                } else {
                    $values .= "NULL, ";
                }
                //$values .= "NULL, ";
            } else if(is_int($item) || is_numeric($item)) {
                if(is_string($item)) {
                    $values .= "'" . $item . "', ";
                } else {
                    $values .= "" . $item . ", ";
                }
            } else if(is_bool($item)) {
                if($item) {
                    $values .= "TRUE, ";
                } else {
                    $values .= "FALSE, ";
                }
            } else {
                $values .= "'" . $item . "', ";
            }
        }
        $values = trim(trim($values), ",");
        return $values;
    }
}
?>
