<?php
namespace RzSDK\SqlQueryBuilder;
?>
<?php
use RzSDK\Utils\ArrayUtils;
use RzSDK\SqlQueryBuilder\UpdateSetSql;
use RzSDK\SqlQueryBuilder\SelectWhereSql;
use RzSDK\Log\DebugLog;
?>
<?php
class UpdateQuery {
    use UpdateSetSql;
    use SelectWhereSql;
    //
    private $sqlQuery;

    public function __construct($table) {
        if(empty($table)) {
            return $this;
        }
        $this->setUpdateTable($table);
        return $this;
    }

    public function build() {
        $this->sqlQuery = "UPDATE"
            . " {$this->toSetStatement()}"
            . " {$this->toWhereStatement()}"
            . " ";
        $this->sqlQuery = preg_replace("/\s+/u", " ", $this->sqlQuery);
        return trim($this->sqlQuery) . ";";
    }
}
?>