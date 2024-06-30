<?php
namespace RzSDK\Utils;
?>
<?php
use Exception;
?>
<?php
class ArrayUtils {
    public static function isMultidimensional(array $array) {
        if(!is_array($array)) {
            return false;
        }
        if(count(array_filter($array,'is_array'))) {
            return true;
        }
        return false;
    }

    public static function isAssociative(array $array) {
        if(empty($array) || is_null($array)) {
            throw new \Exception("Error");
        }
        if(!is_array($array)) {
            return false;
        }
        $keys = array_keys($array);
        /*if(array_is_list($this->columns)) {
            //
        }*/
        if($keys !== range(0, count($array) - 1)) {
            return true;
        }
        return false;
    }
}
?>