<?php
namespace RzSDK\SqlQueryBuilder;
?>
<?php
trait SelectLimitSql {
    private $limit = null;

    public function limit(int $limit): self {
        $this->limit = $limit;
        return $this;
    }

    private function toLimitStatement(): string {
        if(empty($this->limit)) {
            return "";
        }
        return "LIMIT $this->limit";
    }
}
?>