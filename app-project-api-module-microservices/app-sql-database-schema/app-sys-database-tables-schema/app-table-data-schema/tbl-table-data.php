<?php
namespace RzSDK\Database\Schema;
?>
<?php
class TblTableData {
    public static $prefix = "tbl_";
    public static $table = "table_data";
    //public static $foreignTable = TblSubjectInfo::tableWithPrefix();
    //
    public $schema_id       = "schema_id";
    public $id              = "id";
    //public $unique_name     = "unique_name";
    public $table_name      = "table_name";
    public $table_comment   = "table_comment";
    public $column_prefix   = "column_prefix";
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
