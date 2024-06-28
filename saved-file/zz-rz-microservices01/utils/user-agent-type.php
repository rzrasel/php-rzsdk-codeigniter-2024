<?php
namespace RzSDK\User\Type;
?>
<?php
enum UserAgentType: string {
    case ANDROID_APP    = "android_app";
    case IOS_APP        = "ios_app";
    case WEB_SITE       = "WEB_SITE";
}
?>
<?php
//https://github.com/php/php-src/issues/9352
//Retrieving an enum case by its name
function getUserAgentTypeByValue($value) {
    foreach (UserAgentType::cases() as $case) {
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