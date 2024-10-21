<?php
namespace RzSDK\Padding\Property;
?>
<?php
trait SetFullLength {
    //|----------------|SET PADDING TOTAL LENGTH|----------------|
    public function setFullLength(int $length): self {
        $this->fullLength = $length;
        return $this;
    }
}
?>