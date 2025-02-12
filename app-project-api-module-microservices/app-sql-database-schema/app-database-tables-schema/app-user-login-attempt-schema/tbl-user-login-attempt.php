<?php
namespace RzSDK\Database\Schema;
?>
<?php
class TblUserLoginAttempt {
    public static $prefix = "tbl_";
    public static $table = "user_login_attempt";
    //public static $foreignTable = TblSubjectInfo::tableWithPrefix();
    //
    public $user_id         = "user_id";
    public $id              = "id";
    public $attempt_count   = "attempt_count";
    public $attempt_time    = "attempt_time";
    public $success         = "success";
    public $ip_address      = "ip_address";
    public $reason          = "reason";

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
