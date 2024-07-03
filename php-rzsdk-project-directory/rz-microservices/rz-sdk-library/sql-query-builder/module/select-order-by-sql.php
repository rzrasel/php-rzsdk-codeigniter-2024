<?php
namespace RzSDK\SqlQueryBuilder;
?>
<?php
use Exception;
?>
<?php
trait SelectOrderBySql {
    private $order = [];

    public function orderBy(string $column, string $direction = "asc"): self {
        $direction = strtoupper($direction);
        if (!in_array($direction, ["ASC", "DESC"]))
            throw new Exception("Direction should be either ASC or DESC");

        $this->order[] = "$column $direction";
        return $this;
    }

    private function toOrderByStatement(): string {
        if(empty($this->order)) {
            return "";
        }
        return "ORDER BY " . implode(", ", $this->order);
    }
}
?>