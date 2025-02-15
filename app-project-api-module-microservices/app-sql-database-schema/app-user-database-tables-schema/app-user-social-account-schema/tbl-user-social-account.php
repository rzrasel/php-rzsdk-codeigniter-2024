<?php
namespace RzSDK\Database\Schema;
?>
<?php
class TblUserSocialAccount {
    public static $prefix = "tbl_";
    public static $table = "user_social_account";
    //public static $foreignTable = TblSubjectInfo::tableWithPrefix();
    //
    public $user_id         = "user_id";
    public $id              = "id";
    public $social_id       = "social_id";
    public $provider        = "provider";
    public $auth_token      = "auth_token";
    public $is_verified     = "is_verified";
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
