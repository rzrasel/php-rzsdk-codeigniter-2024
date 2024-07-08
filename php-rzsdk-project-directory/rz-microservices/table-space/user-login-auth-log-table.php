<?php
namespace RzSDK\DatabaseSpace;
?>
<?php
class UserLoginAuthLogTable {
    public static $table = "user_login_auth_log";
    //
    public $user_id = "user_id";
    public $status = "status";
    public $assigned_date = "assigned_date";
    public $expired_date = "expired_date";
    public $encrypt_type = "encrypt_type";

    public $mcrypt_key = "mcrypt_key";
    public $mcrypt_iv = "mcrypt_iv";
    public $auth_token = "auth_token";
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
}
?>