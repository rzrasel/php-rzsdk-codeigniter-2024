<?php
namespace RzSDK\SqlQueryBuilder;
?>
<?php
use RzSDK\Utils\ArrayUtils;
use RzSDK\SqlQueryBuilder\SelectWhereSql;
use RzSDK\Log\DebugLog;
?>
<?php
class DeleteQuery {
    use SelectWhereSql;
    //
    private $table;
    private $sqlQuery;

    public function __construct($table) {
        $this->table = $table;
    }

    public function build() {
        $this->sqlQuery = "DELETE"
            . " {$this->bindDeleteStatement()}"
            . " ";
        $this->sqlQuery = preg_replace("/\s+/u", " ", $this->sqlQuery);
        return trim($this->sqlQuery) . ";";
    }

    private function bindDeleteStatement() {
        if(empty($this->table)) {
            return "";
        }
        return "FROM {$this->table} {$this->toWhereStatement()}";
    }
}
?>