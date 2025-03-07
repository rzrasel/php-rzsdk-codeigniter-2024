<?php
namespace RzSDK\Database\Schema;
?>
<?php
class TblUserLastActivity {
    public static $prefix = "tbl_";
    public static $table = "users_last_activity";
    //public static $foreignTable = TblSubjectInfo::tableWithPrefix();
    //
    public $user_id         = "user_id";
    public $id              = "id";
    public $last_login      = "last_login";
    public $last_active     = "last_active";
    public $last_seen_at    = "last_seen_at";
    public $failed_attempts = "failed_attempts";
    public $lockout_until   = "lockout_until";
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
