<?php
namespace RzSDK\Database\Quiz;
?>
<?php
class TblBookInfo {
    public static $prefix = "";
    public static $table = "book_info";
    //
    public $lan_id          = "lan_id";
    public $book_token_id   = "book_token_id";
    public $book_info_id   = "book_info_id";
    public $book_info_name = "book_info_name";
    public $book_name_prefix    = "book_name_prefix";
    public $book_name_suffix    = "book_name_suffix";
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