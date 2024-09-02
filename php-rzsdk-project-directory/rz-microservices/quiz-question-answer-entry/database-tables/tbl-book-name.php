<?php
namespace RzSDK\Database\Quiz;
?>
<?php
class TblBookName {
    public static $prefix = "";
    public static $table = "book_name";
    //
    public $lan_id          = "lan_id";
    public $book_token_id   = "book_token_id";
    public $book_name_id    = "book_name_id";
    public $book_name       = "book_name";
    public $slug            = "slug";
    public $status          = "status";
    public $is_default      = "is_default";
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