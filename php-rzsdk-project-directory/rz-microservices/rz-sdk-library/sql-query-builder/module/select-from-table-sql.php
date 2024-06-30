<?php
namespace RzSDK\SqlQueryBuilder;
?>
<?php
use RzSDK\Utils\ArrayUtils;
use RzSDK\Log\DebugLog;
?>
<?php
trait SelectFromTableSql {
    protected $fromTable;
    protected $fromAlias;

    public function from($table, $alias = "") {
        $this->fromTable = $table;
        $this->fromAlias = $alias;
        return $this;
    }

    private function toFromTableSql(): string {
        if(is_array($this->fromTable)) {
            $retVal = "";
            if(ArrayUtils::isAssociative($this->fromTable)) {
                foreach($this->fromTable as $key => $value) {
                    $retVal .= "{$key} AS {$value}, ";
                }
                return trim(trim($retVal), ",");
            } else {
                return trim(implode(", ", array_values($this->fromTable)));
            }
        } else {
            if(!empty($this->fromTableAlias)) {
                return trim($this->fromTable) . " AS " . trim($this->fromTableAlias);
            }
            return trim($this->fromTable);
        }
    }
}
?>