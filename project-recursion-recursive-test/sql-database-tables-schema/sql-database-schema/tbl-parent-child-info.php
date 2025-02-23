<?php
namespace RzSDK\Database\Schema;
?>
<?php
class TblParentChildInfo {
    public static $prefix = "tbl_";
    public static $table = "parent_child_info";
    //public static $foreignTable = TblSubjectInfo::tableWithPrefix();
    //
    public $id          = "id";
    public $parent_id   = "parent_id";
    public $name        = "name";

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