<?php
namespace RzSDK\String\Utils;
?>
<?php
enum TextCase: string {
    case ALIKE      = "Alike Case";
    case LOWER      = "Lower Case";
    case UCFIRST    = "UcFirst Case";
    case UCWORDS    = "UcWords Case";
    case UPPER      = "Upper Case";
}
?>
<?php
//https://github.com/php/php-src/issues/9352
//Retrieving an enum case by its name
function getTextCaseByValue($value) {
    foreach(TextCase::cases() as $case) {
        /* if ($case->name === $enumName) {
            return $case;
        } */
        if($case->value === $value) {
            return $case;
        }
    }
    return null;
}
?>
