<?php
namespace RzSDK\Padding\Utils;
?>
<?php
enum PaddingPlace: string {
    case CENTER = "Center Padding";
    case LEFT   = "Left Padding";
    case RIGHT  = "Right Padding";
}
?>
<?php
//https://github.com/php/php-src/issues/9352
//Retrieving an enum case by its name
function getPaddingPlaceByValue($value) {
    foreach(PaddingPlace::cases() as $case) {
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
