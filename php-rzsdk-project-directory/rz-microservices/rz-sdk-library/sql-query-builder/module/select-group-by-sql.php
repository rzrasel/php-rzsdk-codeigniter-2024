<?php
namespace RzSDK\SqlQueryBuilder;
?>
<?php
use RzSDK\Utils\ArrayUtils;
use RzSDK\Log\DebugLog;
?>
<?php
trait SelectGroupBySql {
    public function groupBy(): self {
        return $this;
    }
}
?>