<?php
namespace RzSDK\Database\Schema;
?>
<?php
class TblUserSession {
    public static $prefix = "tbl_";
    public static $table = "user_session";
    //public static $foreignTable = TblSubjectInfo::tableWithPrefix();
    //
    public $user_id         = "user_id";
    public $id              = "id";
    public $hash_type       = "hash_type";
    public $session_salt    = "session_salt";
    public $session_id      = "session_id";
    public $session_duration    = "session_duration";
    public $expires_at      = "expires_at";
    public $refresh_token   = "refresh_token";
    public $ip_address      = "ip_address";
    public $user_agent      = "user_agent";
    public $device          = "device";
    public $browser         = "browser";
    public $os              = "os";
    public $login_time      = "login_time";
    public $logout_time     = "logout_time";
    public $remember_me     = "remember_me";
    public $status          = "status";
    public $modified_date   = "modified_date";
    public $created_date    = "created_date";
    public $modified_by     = "modified_by";
    public $created_by      = "created_by";

    public static function table() {
        return self::$table;
    }

    public static function tableWithPrefix() {
        return trim(trim(self::$prefix, "_") . "_" . self::$table, "_");
    }

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
