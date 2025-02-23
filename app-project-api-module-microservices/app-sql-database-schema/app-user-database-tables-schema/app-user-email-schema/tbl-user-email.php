<?php
namespace RzSDK\Database\Schema;
?>
<?php
class TblUserEmail {
    public static $prefix = "tbl_";
    public static $table = "user_email";
    //public static $foreignTable = TblSubjectInfo::tableWithPrefix();
    //
    public $user_id         = "user_id";
    public $id              = "id";
    public $email           = "email";
    public $provider        = "provider";
    public $is_primary      = "is_primary";
    public $verification_code   = "verification_code";
    public $last_verification_sent_at   = "last_verification_sent_at";
    public $verification_code_expiry    = "verification_code_expiry";
    public $verification_status = "verification_status";
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
