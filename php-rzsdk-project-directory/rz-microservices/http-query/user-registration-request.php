<?php
namespace RzSDK\HTTPRequest;
?>
<?php
use RzSDK\HTTPRequest\ValidationType;
?>
<?php
class UserRegistrationRequest {
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

    public function auth_type_rules() {
        return array(
            ValidationType::NOT_NULL,
        );
    }

    public function user_email_rules() {
        return array(
            ValidationType::NOT_NULL,
            ValidationType::EMAIL,
        );
    }

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