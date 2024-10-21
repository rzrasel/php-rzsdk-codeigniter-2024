<?php
namespace RzSDK\Padding\Property;
?>
<?php
trait SetFullLength {
    public function setFullLength(int $length): self {
        $this->fullLength = $length;
        return $this;
    }
}
?>