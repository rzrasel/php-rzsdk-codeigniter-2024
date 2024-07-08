<?php
namespace RzSDK\Encryption;
?>
<?php
//defined("RZ_SDK_BASEPATH") OR exit("No direct script access allowed");
//defined("RZ_SDK_WRAPPER") OR exit("No direct script access allowed");
?>
<?php
use RzSDK\Encryption\EncryptionType;
?>
<?php
class DbTypeExtension {
    public static function getEncryptionTypeByValue($value) {
        foreach (EncryptionType::cases() as $case) {
            /* if ($case->name === $enumName) {
                return $case;
            } */
            if ($case->value === $value) {
                return $case;
            }
        }
        return null;
    }
}
?>