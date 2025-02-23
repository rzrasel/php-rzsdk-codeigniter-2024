<?php
namespace RzSDK\Padding\Property;
?>
<?php
use RzSDK\String\Utils\TextCase;
?>
<?php
trait SetTextCase {
    public function setTextCase(TextCase $textCase): self {
        $this->textCase = $textCase;
        return $this;
    }
}
?>