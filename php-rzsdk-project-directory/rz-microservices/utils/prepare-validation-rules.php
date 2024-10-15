<?php
namespace RzSDK\Validation;
?>
<?php
use RzSDK\HTTPRequest\ValidationType;
?>
<?php
class PrepareValidationRules {
    public function __construct() {}

    public function emailValidationRules(): array {
        return array(
            "key"       => ValidationType::EMAIL,
            "values"    => array(),
            "message"   => "Invalid email address",
        );
    }

    public function minLengthValidationRules($length, $message): array {
        return array(
            "key"       => ValidationType::MIN_LENGTH,
            "values"    => array("length" => $length),
            "message"   => $message,
        );
    }

    public function maxLengthValidationRules($length, $message): array {
        return array(
            "key"       => ValidationType::MAX_LENGTH,
            "values"    => array("length" => $length),
            "message"   => $message,
        );
    }

    public function notNullValidationRules($message): array {
        return array(
            "key"       => ValidationType::NOT_NULL,
            "values"    => array(),
            "message"   => $message,
        );
    }

    public function noWhiteSpaceValidationRules($message): array {
        return array(
            "key"       => ValidationType::NO_SPACE,
            "values"    => array(),
            "message"   => $message,
        );
    }

    public function passwordValidationRules($message) {
        return array(
            "key"       => ValidationType::PASSWORD,
            "values"    => array(),
            "message"   => $message,
        );
    }
}
?>