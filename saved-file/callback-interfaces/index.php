<?php
require_once("include.php");
?>
<?php
$people = new people();
$people->setIEat(new Chinese());
$people->eatService();
?>
<?php
/* $people = new people();
$people->setIEat(new IEat() {
    public function eat() {
        echo "I need **** to eat";
    }
});
$people->eatService(); */
?>