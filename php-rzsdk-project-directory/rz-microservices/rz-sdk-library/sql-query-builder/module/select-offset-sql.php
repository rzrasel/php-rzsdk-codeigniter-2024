<?php
namespace RzSDK\SqlQueryBuilder;
?>
<?php
trait SelectOffsetSql {
    private $offset = null;

    public function offset(int $offset): self {
        $this->offset = $offset;
        return $this;
    }

    private function toOffsetSql(): string {
        if(empty($this->offset)) {
            return "";
        }
        return "OFFSET $this->offset";
    }
}
?>