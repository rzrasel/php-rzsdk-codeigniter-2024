<?php
namespace RzSDK\SqlQueryBuilder;
?>
<?php
use RzSDK\SqlQueryBuilder\SqlOrderType;
use Exception;
?>
<?php
trait SelectOrderBySql {
    private $order = [];

    public function orderBy(string $column, SqlOrderType $direction = SqlOrderType::ASC): self {
        //$direction = strtoupper($direction);
        $direction = $direction->value;
        if (!in_array($direction, ["ASC", "DESC"]))
            throw new Exception("Direction should be either ASC or DESC");

        $this->order[] = trim("{$column} {$direction}");
        return $this;
    }

    public function orderByWithTable(string $table, string $column, SqlOrderType $direction = SqlOrderType::ASC): self {
        //$direction = strtoupper($direction);
        $direction = $direction->value;
        if (!in_array($direction, ["ASC", "DESC"]))
            throw new Exception("Direction should be either ASC or DESC");

        $this->order[] = trim("{$table}.{$column} {$direction}", ".");
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