<?php
namespace RzSDK\Padding\Property;
?>
<?php
?>
<?php
trait SetSourceText {
    public function setSourceText(string $string): self {
        $this->sourceText = trim($string);
        return $this;
    }
}
?>