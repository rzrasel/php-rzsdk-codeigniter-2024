<?php
namespace RzSDK\User\Type;
?>
<?php
enum UserDeviceType: string {
    case ANDROID    = "android";
    case IOS        = "ios";
    case WINDOWS    = "windows";
}
?>
<?php
//https://github.com/php/php-src/issues/9352
//Retrieving an enum case by its name
function getUserDeviceTypeByValue($value) {
    foreach (UserDeviceType::cases() as $case) {
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