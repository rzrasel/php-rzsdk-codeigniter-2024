<?php
namespace RzSDK\Padding\Property;
?>
<?php
trait SetNumberOfTabs {
    public function setNumberOfTabs(int $numOfTabs): self {
        $this->tabCount = $numOfTabs;
        return $this;
    }
}
?>