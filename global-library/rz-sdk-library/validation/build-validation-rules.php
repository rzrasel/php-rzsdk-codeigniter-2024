<?php
namespace RzSDK\Data\Validation\Build;
?>
<?php
use RzSDK\Data\Validation\Rule\RegularValidation;
use RzSDK\Data\Validation\Rule\EmailValidation;
use RzSDK\Data\Validation\Rule\PasswordValidation;
use RzSDK\Data\Validation\Type\ValidationType;
use RzSDK\Log\DebugLog;
?>
<?php
class BuildValidationRules {
    //
    private $value;
    private $validationRules;

    //

    public function __construct() {
    }

    public function setRules($value, $validationRules) {
        $this->value = $value;
        $this->validationRules = $validationRules;
        return $this;
    }

    public function run() {
        $isValidated = true;
        $message = "";
        //echo $this->value . "-----------";
        foreach($this->validationRules as $rules) {
            if($rules["key"] == ValidationType::NOT_NULL) {
                if(RegularValidation::isEmptyOrNull($this->value)) {
                    $message = $rules["message"];
                    $isValidated = false;
                }
            } else if($rules["key"] == ValidationType::MIN_LENGTH) {
                if(RegularValidation::isMinStrLen($this->value, $rules["values"]["length"])) {
                    $message = $rules["message"];
                    $isValidated = false;
                }
            } else if($rules["key"] == ValidationType::MAX_LENGTH) {
                if(RegularValidation::isMaxStrLen($this->value, $rules["values"]["length"])) {
                    $message = $rules["message"];
                    $isValidated = false;
                }
            } else if($rules["key"] == ValidationType::NO_SPACE) {
                if(RegularValidation::haveWhiteSpace($this->value)) {
                    $message = $rules["message"];
                    $isValidated = false;
                }
            } else if($rules["key"] == ValidationType::EMAIL) {
                if(!EmailValidation::isEmail($this->value)) {
                    $message = $rules["message"];
                    $isValidated = false;
                }
            } else if($rules["key"] == ValidationType::PASSWORD) {
                if(!PasswordValidation::isPassword($this->value)) {
                    $message = $rules["message"];
                    $isValidated = false;
                }
            }
            if(!$isValidated) {
                return array(
                    "is_validate"   => $isValidated,
                    "message"       => $message,
                );
            }
        }
        return array(
            "is_validate"   => $isValidated,
            "message"       => $message,
        );
    }
}
?>