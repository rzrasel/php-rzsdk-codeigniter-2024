<?php
namespace RzSDK\User\Type;
?>
<?php
class UserAuthTypeExtension {
    public static function getUserAuthTypeByValue($value) {
        foreach (UserAuthType::cases() as $case) {
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
<?php
//https://github.com/php/php-src/issues/9352
//Retrieving an enum case by its name
?>
