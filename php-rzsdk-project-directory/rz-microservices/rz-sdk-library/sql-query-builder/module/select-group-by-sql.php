<?php
namespace RzSDK\SqlQueryBuilder;
?>
<?php
use RzSDK\Utils\ArrayUtils;
use RzSDK\Log\DebugLog;
?>
<?php
trait SelectGroupBySql {
    protected $groupBy;

    public function groupBy($groupBy): self {
        if(empty($groupBy)) {
            return $this;
        }
        $this->groupBy[] = $groupBy;
        return $this;
    }

    private function toGroupByStatement() {
        if(empty($this->groupBy)) {
            return "";
        }
        return $this->bindGroupByStatement();
    }

    private function bindGroupByStatement() {
        $retVal = "GROUP BY ";
        foreach($this->groupBy as $item) {
            $retVal .= $item . ", ";
        }
        return trim($retVal, ", ");
    }
}
?>