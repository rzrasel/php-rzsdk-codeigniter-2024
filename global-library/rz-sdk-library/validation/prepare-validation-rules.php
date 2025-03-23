<?php
namespace RzSDK\Data\Validation\Preparation;
?>
<?php
use RzSDK\Data\Validation\Type\ValidationType;
?>
<?php
class PrepareValidationRules {
    public function __construct() {}

    public function emailRule($message = "Invalid email address"): array {
        return array(
            "key"       => ValidationType::EMAIL,
            "values"    => array(),
            "message"   => $message,
        );
    }

    public function minLengthRule($length, $message): array {
        return array(
            "key"       => ValidationType::MIN_LENGTH,
            "values"    => array("length" => $length),
            "message"   => $message,
        );
    }

    public function maxLengthRule($length, $message): array {
        return array(
            "key"       => ValidationType::MAX_LENGTH,
            "values"    => array("length" => $length),
            "message"   => $message,
        );
    }

    public function notNullRule($message): array {
        return array(
            "key"       => ValidationType::NOT_NULL,
            "values"    => array(),
            "message"   => $message,
        );
    }

    public function noWhiteSpaceRule($message): array {
        return array(
            "key"       => ValidationType::NO_SPACE,
            "values"    => array(),
            "message"   => $message,
        );
    }

    public function passwordRule($message) {
        return array(
            "key"       => ValidationType::PASSWORD,
            "values"    => array(),
            "message"   => $message,
        );
    }
}
?>