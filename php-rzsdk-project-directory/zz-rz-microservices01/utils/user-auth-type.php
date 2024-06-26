<?php
namespace RzSDK\User\Type;
?>
<?php
enum UserAuthType: string {
    case EMAIL      = "email";
    case FACEBOOK   = "facebook";
    case GOOGLE     = "google";
    case MOBILE     = "mobile";
}
?>
<?php
//https://github.com/php/php-src/issues/9352
//Retrieving an enum case by its name
function getUserAuthTypeByValue($value) {
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
?>