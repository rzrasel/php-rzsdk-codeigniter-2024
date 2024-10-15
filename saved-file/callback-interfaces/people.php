<?php
require_once("include.php");
?>
<?php
class People {
    private IEat $iEat;
    public function setIEat(IEat $iEat) {
        $this->iEat = $iEat;
    }

    public function eatService() {
        $this->iEat->eat();
    }
}
?>