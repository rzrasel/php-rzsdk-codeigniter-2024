<?php
namespace RzSDK\HTTPRequest;
?>
<?php
class UserRegistrationRequest {
    //
    public $device_type  = "device_type";
    public $auth_type    = "auth_type";
    public $agent_type   = "agent_type";
    public $user_email       = "user_email";
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
}
?>