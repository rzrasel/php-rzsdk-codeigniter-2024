<?php
namespace RzSDK\Database\Schema;
?>
<?php
class TblColumnKey {
    public static $prefix = "tbl_";
    public static $table = "column_key";
    //public static $foreignTable = TblSubjectInfo::tableWithPrefix();
    //
    //public $column_id       = "column_id";
    public $id              = "id";
    public $working_table   = "working_table";
    public $main_column     = "main_column";
    public $key_type        = "key_type";
    //public $reference_table = "reference_table";
    public $reference_column    = "reference_column";
    public $key_name        = "key_name";
    public $unique_name     = "unique_name";
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
