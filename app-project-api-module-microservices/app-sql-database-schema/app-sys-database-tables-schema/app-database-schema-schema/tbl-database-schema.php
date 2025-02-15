<?php
namespace RzSDK\Database\Schema;
?>
<?php
class TblDatabaseSchema {
    public static $prefix = "tbl_";
    public static $table = "database_schema";
    //public static $foreignTable = TblSubjectInfo::tableWithPrefix();
    //
    public $id              = "id";
    public $schema_name     = "schema_name";
    public $schema_version  = "schema_version";
    public $table_prefix    = "table_prefix";
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
