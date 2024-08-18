<?php
namespace RzSDK\Database\Book;
?>
<?php
class TblReligion {
    public static $prefix = "tbl_";
    public static $table = "religion";
    //
    public $religion_id     = "religion_id";
    public $religion_name_bn    = "religion_name_bn";
    public $religion_name_en    = "religion_name_en";
    public $status          = "status";
    public $modified_by     = "modified_by";
    public $created_by      = "created_by";
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