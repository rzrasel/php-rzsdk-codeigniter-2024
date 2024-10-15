<?php
namespace RzSDK\Database\Book;
?>
<?php
class TblSectionInfo {
    public static $prefix = "tbl_";
    public static $table = "section_info";
    //
    public $lan_id         = "lan_id";
    public $section_id      = "section_id";
    public $section_parent_id   = "section_parent_id";
    public $section_info_id = "section_info_id";
    public $section_name    = "section_name";
    public $section_order   = "section_order";
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