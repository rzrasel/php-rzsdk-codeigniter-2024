<?php
namespace RzSDK\Database\Schema;
?>
<?php
class TblColumnCompositeKey {
    public static $prefix = "tbl_";
    public static $table = "composite_key";
    //public static $foreignTable = TblSubjectInfo::tableWithPrefix();
    //
    public $column_id       = "column_id";
    public $key_id          = "key_id";
    public $id              = "id";
    public $key_name        = "key_name";
    public $modified_date   = "modified_date";
    public $created_date    = "created_date";

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
