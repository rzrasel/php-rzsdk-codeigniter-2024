<?php
namespace RzSDK\DateTime;
?>
<?php
enum DateDiffType: string {
    case years      = "years";
    case months     = "months";
    case days       = "days";
    case hours      = "hours";
    case minutes    = "minutes";
    case seconds    = "seconds";
}
?>
<?php
//https://github.com/php/php-src/issues/9352
//Retrieving an enum case by its name
function getDateDiffTypeByValue($value) {
    foreach (DateDiffType::cases() as $case) {
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
