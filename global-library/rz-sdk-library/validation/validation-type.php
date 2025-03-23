<?php
namespace RzSDK\Data\Validation\Type;
?>
<?php
enum ValidationType: string {
    case EMAIL      = "email";
    case MAX_LENGTH = "max_length";
    case MIN_LENGTH = "min_length";
    case NOT_NULL   = "not_null";
    case PASSWORD   = "password";
    case NO_SPACE   = "no_space";

    public static function getByValue($value): ?self {
        foreach(self::cases() as $props) {
            /* if ($case->name === $enumName) {
                return $case;
            } */
            if($props->value === $value) {
                return $props;
            }
        }
        return null;
    }
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