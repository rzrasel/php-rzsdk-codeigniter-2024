<?php
namespace RzSDK\Database;
?>
<?php
use RzSDK\Database\DbType;
?>
<?php
class DbTypeExtension {
    public static function getDbTypeByValue($value) {
        foreach (DbType::cases() as $case) {
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
