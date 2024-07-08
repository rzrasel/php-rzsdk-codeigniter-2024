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

    private function toOffsetStatement(): string {
        if(!is_int($this->offset) && !isset($this->offset)) {
            if(empty($this->offset)) {
                return "";
            }
        }
        return "OFFSET $this->offset";
    }
}
?>