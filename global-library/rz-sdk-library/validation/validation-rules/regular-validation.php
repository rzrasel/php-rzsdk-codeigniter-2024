<?php
namespace RzSDK\Data\Validation\Rule;
?>
<?php
class RegularValidation {
    public function __construct() {
    }

    public static function isEmptyOrNull($value) {
        if(empty(trim($value))) {
            return true;
        }
        return false;
    }

    public static function isMinStrLen($value, $length) {
        if(strlen($value) < $length) {
            return true;
        }
        return false;
    }

    public static function isMaxStrLen($value, $length) {
        if(strlen($value) > $length) {
            return true;
        }
        return false;
    }

    public static function haveWhiteSpace($value) {
        if(preg_match("/\s/", $value)) {
            return true;
        }
        return false;
    }
}
?>