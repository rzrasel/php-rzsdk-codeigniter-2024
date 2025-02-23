<?php
namespace RzSDK\Padding\Property;
?>
<?php
use RzSDK\Padding\Utils\PaddingPlace;
?>
<?php
trait SetPaddingEdge {
    public function setPaddingPlace(PaddingPlace $paddingPlace): self {
        $this->paddingWings = $paddingPlace;
        return $this;
    }
}
?>