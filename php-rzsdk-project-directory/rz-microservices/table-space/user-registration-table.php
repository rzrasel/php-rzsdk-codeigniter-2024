<?php
namespace RzSDK\DatabaseSpace;
?>
<?php
class UserRegistrationTable {
    public static $table = "user_registration";
    //
    public $user_regi_id = "user_regi_id";
    public $email = "email";
    public $status = "status";
    public $is_verified = "is_verified";
    public $regi_date = "regi_date";
    public $device_type = "device_type";
    public $auth_type = "auth_type";
    public $agent_type = "agent_type";
    public $regi_os = "regi_os";
    public $regi_device = "regi_device";
    public $regi_browser = "regi_browser";
    public $regi_ip = "regi_ip";
    public $regi_http_agent = "regi_http_agent";
    public $modified_by = "modified_by";
    public $created_by = "created_by";
    public $modified_date = "modified_date";
    public $created_date = "created_date";
    //
    //public $test = "theFunc";

    public function getColumn() {
        $result = array_intersect_key(
            get_object_vars($this),
            get_mangled_object_vars($this)
        );
        return array_keys($result);
    }

    public function getColumnWithKey() {
        return array_intersect_key(
            get_object_vars($this),
            get_mangled_object_vars($this)
        );
    }

    public function theFunc() {
        return "run theFunc";
    }
}
?>