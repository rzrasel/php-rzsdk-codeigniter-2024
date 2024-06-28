<?php
namespace RzSDK\SqlQueryBuilder;
?>
<?php
class SelectQuery {
    //
    protected $table;
    protected $columns;
    private $sqlQuery;
    //
    function setColumns($columns) {
        $this->columns = $columns;
        return $this;
    }

    public function build() {
        $this->sqlQuery = "SELECT {$this->bindColumns()}";
        return $this->sqlQuery;
    }

    private function bindColumns() {
        if(empty($this->columns)) {
            return "*";
        }
        //
        /*if(array_is_list($this->columns)) {
            //
        }*/
        $keys = array_keys($this->columns);
        if ($keys !== range(0, count($this->columns) - 1)) {
            // Associative array
            $column = "";
            foreach ($this->columns as $key => $value) {
                $column .= "{$key} AS {$value}, ";
            }
            return trim(trim($column), ",");
        } else {
            // Sequential array
            return trim(implode(", ", array_values($this->columns)));
        }
    }
}
?>