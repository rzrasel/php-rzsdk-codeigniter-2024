<?php
namespace RzSDK\Identification;
?>
<?php
class RandomIdGenerator {
    public static function getRandomString($length = 10) {
        $characters = "0123456789"
            . "abcdefghijklmnopqrstuvwxyz"
            . "ABCDEFGHIJKLMNOPQRSTUVWXYZ"
            . "@%$#^&*()-=~{}[]+_"
            . "";
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}
?>