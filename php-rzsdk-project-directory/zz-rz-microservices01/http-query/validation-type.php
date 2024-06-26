<?php
namespace RzSDK\HTTPRequest;
?>
<?php
enum ValidationType: string {
    case NOT_NULL = "not_null";
    case EMAIL  = "email";
    case PASSWORD  = "password";
}
?>
<?php
//https://github.com/php/php-src/issues/9352
//Retrieving an enum case by its name
function getValidationTypeByValue($value) {
    foreach (ValidationType::cases() as $case) {
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