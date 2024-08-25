<?php
namespace RzSDK\Database\Book;
?>
<?php
class TblSection {
    public static $prefix = "tbl_";
    public static $table = "section";
    //
    public $lan_id         = "lan_id";
    public $section_id      = "section_id";
    public $section_token   = "section_token";
    public $slug            = "slug";
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