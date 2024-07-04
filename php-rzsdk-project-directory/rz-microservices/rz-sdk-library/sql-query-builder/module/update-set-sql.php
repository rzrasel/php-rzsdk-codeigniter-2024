<?php
namespace RzSDK\SqlQueryBuilder;
?>
<?php
use RzSDK\Utils\ArrayUtils;
use RzSDK\Log\DebugLog;
?>
<?php
trait UpdateSetSql {
    private $updateColumns = [];
    private $updateTable;

    private function setUpdateTable($table) {
        $this->updateTable = $table;
    }
    public function set($column) {
        if(empty($column)) {
            return $this;
        }
        if(ArrayUtils::isMultidimensional($column)) {
            $this->updateColumns = $column;
        } else {
            $this->updateColumns[] = $column;
        }
        return $this;
    }

    private function toSetStatement() {
        if(empty($this->updateColumns)) {
            return "";
        }
        return "{$this->updateTable} SET {$this->bindSetStatement()}";
    }

    private function bindSetStatement() {
        if(empty($this->updateColumns)) {
            return "";
        }
        $retVal = "";
        foreach($this->updateColumns as $arrayItems) {
            foreach($arrayItems as $key => $value) {
                if(is_bool($value)) {
                    $value = "TRUE";
                } else if(is_int($value)) {
                    $value = $value;
                } else {
                    $value = "'{$value}'";
                }
                $retVal .= "{$key} = {$value}, ";
            }
        }
        return trim($retVal, ", ");
    }
}
?>