<?php
require_once("include.php");
?>
<?php
class Chinese implements IEat {
    public function eat() {
        echo "Meow";
    }
}
?>
<?php
interface IEatAdvance {
    public function eat($object);
}
class ChineseAdvancedImpl implements IEatAdvance {
    public function eat($object): ChineseAdvanced {
        return $object;
    }
}

class ChineseAdvanced {
    public function __construct() {
        $people = new People();
        $people->setIEat(new class implements IEatAdvance {
            public function eat($object) {
                echo "asfkjsjdl";
            }
        });
    }
}
?>
