<?php
namespace RzSDK\DatabaseSpace;
?>
<?php
class UserLoginAuthLogTable {
    public static $table = "user_login_auth_log";
    //
    public $user_id = "user_id";
    public $status = "status";
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