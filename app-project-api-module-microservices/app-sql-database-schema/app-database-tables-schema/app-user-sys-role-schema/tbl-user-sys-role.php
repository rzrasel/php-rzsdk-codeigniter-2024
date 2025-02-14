<?php
namespace RzSDK\Database\Schema;
?>
<?php
class TblUserSysRole {
    public static $prefix = "tbl_";
    public static $table = "user_sys_role";
    //public static $foreignTable = TblSubjectInfo::tableWithPrefix();
    //
    public $id              = "id";
    public $name            = "name";
    public $description     = "description";
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
