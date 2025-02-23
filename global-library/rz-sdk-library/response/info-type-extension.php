<?php
namespace RzSDK\Response;
?>
<?php
class InfoTypeExtension {
    //https://github.com/php/php-src/issues/9352
    //Retrieving an enum case by its name
    public static function getInfoTypeByValue($value) {
        foreach (InfoType::cases() as $case) {
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