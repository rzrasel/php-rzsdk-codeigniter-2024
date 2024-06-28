<?php
namespace RzSDK\DatabaseSpace;
?>
<?php
enum DbType: string {
    case SQLITE = "SQLite";
    case MYSQL  = "MySQL";
}
?>
<?php
//https://github.com/php/php-src/issues/9352
//Retrieving an enum case by its name
function getDbTypeByValue($value) {
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
?>