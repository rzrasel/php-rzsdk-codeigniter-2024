<?php
namespace RzSDK\Identification;
?>
<?php
class UniqueIntId {
    //private currentMicrotime = round(microtime(true) * 1000);
    //private currentMicrotime;
    public function __construct() {
        //echo __CLASS__ . " == " . __METHOD__;
    }

    public function getMicrotime() {
        return round(microtime(true) * 1000);
    }
    
    public function getRandomDigits($length, $isStartWithZero = true) {
        $length = intval($length, 10);
        $output = "";
        if(!$isStartWithZero) {
            $output = mt_rand(1, 9);
        } else {
            $output = mt_rand(0, 9);
        }
        for ($i = 0; $i < $length - 1; $i++) {
            $output .= mt_rand(0, 9);
        }
        return $output;
    }
    
    public function getId() {
        return $this->getMicrotime() . "" . $this->getRandomDigits(5, false);
    }
}
?>
<?php
?>
