<?php
namespace RzSDK\HTTPRequest;
?>
<?php
use RzSDK\Validation\PrepareValidationRules;
use RzSDK\HTTPRequest\ValidationType;
?>
<?php
class UserAuthenticationRequest {
    //
    public $device_type = "device_type";
    public $auth_type   = "auth_type";
    public $agent_type  = "agent_type";
    public $user_email  = "user_email";
    public $password    = "password";
    //
    public function __construct() {
    }

    public function getQuery() {
        $result = array_intersect_key(
            get_object_vars($this),
            get_mangled_object_vars($this)
        );
        return array_keys($result);
    }

    public function getQueryWithKey() {
        return array_intersect_key(
            get_object_vars($this),
            get_mangled_object_vars($this)
        );
    }

    public function device_type_rules() {
        $validationRules = new PrepareValidationRules();
        return array(
            $validationRules->notNullValidationRules("Device type can not be null"),
        );
    }

    public function auth_type_rules() {
        $validationRules = new PrepareValidationRules();
        return array(
            $validationRules->notNullValidationRules("Auth type can not be null"),
        );
    }

    public function agent_type_rules() {
        $validationRules = new PrepareValidationRules();
        return array(
            $validationRules->notNullValidationRules("Agent type can not be null"),
        );
    }

    public function user_email_rules() {
        $minLength = 5;
        $maxLength = 256;
        $validationRules = new PrepareValidationRules();
        return array(
            $validationRules->notNullValidationRules("User email can not be null"),
            $validationRules->noWhiteSpaceValidationRules("User email can not contain any white space"),
            $validationRules->minLengthValidationRules($minLength, "User email length can not less than  " . $minLength . "  character"),
            $validationRules->maxLengthValidationRules($maxLength, "User email length can not more than  " . $maxLength . "  character"),
            $validationRules->emailValidationRules(),
        );
    }

    public function password_rules() {
        $minLength = 8;
        $maxLength = 80;
        $validationRules = new PrepareValidationRules();
        return array(
            $validationRules->notNullValidationRules("Password can not be null"),
            $validationRules->noWhiteSpaceValidationRules("Password can not contain any white space"),
            $validationRules->minLengthValidationRules($minLength, "Password length can not less than  " . $minLength . "  character"),
            $validationRules->maxLengthValidationRules($maxLength, "Password length can not more than  " . $maxLength . "  character"),
            $validationRules->passwordValidationRules("Invalid password"),
        );
    }

    /*
     * Array key mapping
     * Array key mapping with request parameter and database column
     * Array left side is - request parameter
     * Array right side is - database column
     */
    public function keyMapping() {
        return array(
            $this->device_type  => "device_type",
            $this->auth_type    => "auth_type",
            $this->agent_type   => "agent_type",
            $this->user_email   => "email",
            $this->password     => "password",
        );
    }
}
?>