<?php
namespace RzSDK\DateTime;
?>
<?php
use RzSDK\DateTime\DateDiffType;
?>
<?php
class DateDiffTypeExtension {
    public static function getDateDiffTypeByValue($value) {
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
}
?>
<?php
//https://github.com/php/php-src/issues/9352
//Retrieving an enum case by its name
?>
