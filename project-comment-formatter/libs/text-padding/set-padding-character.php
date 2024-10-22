<?php
namespace RzSDK\Padding\Property;
?>
<?php
trait SetPaddingCharacter {
    public function setPaddingCharacter(string $string): self {
        $this->padString = $string;
        return $this;
    }
}
?>