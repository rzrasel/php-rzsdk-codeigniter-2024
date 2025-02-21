<?php
namespace RzSDK\Database\Schema;
?>
<?php
class TblColumnData {
    public static $prefix = "tbl_";
    public static $table = "column_data";
    //public static $foreignTable = TblSubjectInfo::tableWithPrefix();
    //
    public $table_id        = "table_id";
    public $id              = "id";
    //public $unique_name     = "unique_name";
    public $column_order    = "column_order";
    public $column_name     = "column_name";
    public $data_type       = "data_type";
    public $is_nullable     = "is_nullable";
    public $have_default    = "have_default";
    public $default_value   = "default_value";
    public $column_comment  = "column_comment";
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
