<?php
namespace RzSDK\SqlQuery;
?>
<?php
class InsertQuery {
    //
    protected $table;
    protected $tableData;
    private $sqlQuery;
    //
    public function __construct() {}

    function setTable($table) {
        $this->table = $table;
        return $this;
    }

    public function values(array $row) {
        if (array_values($row) == $row)
            throw new InvalidArgumentException("Value should be an associative array");
        $this->tableData = $row;
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
        foreach (array_values($this->tableData) as $item) {
            if(empty($item)) {
                $values .= "NULL, ";
            } else if(is_int($item) || is_numeric($item)) {
                $values .= "" . $item . ", ";
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
