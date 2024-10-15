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
<?php
/*$sqlQueryBuilder = new SqlQueryBuilder();
$sqlQuery = $sqlQueryBuilder
    ->update("user_info")
    ->set(array("test1" => "test2"))
    ->set(array("test1" => true))
    ->where("", array("ttttt" => "kljjl lkjj"))
    ->where("", array("test2" => "44444"))
    ->build();
DebugLog::log($sqlQuery);*/
?>
