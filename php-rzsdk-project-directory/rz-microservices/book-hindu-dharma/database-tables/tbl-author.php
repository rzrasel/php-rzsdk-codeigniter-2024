<?php
namespace RzSDK\Database\Book;
?>
<?php
class TblAuthor {
    public static $prefix = "tbl_";
    public static $table = "author";
    //
    public $lan_id         = "lan_id";
    public $author_id       = "author_id";
    public $author_token    = "author_token";
    //public $author_name     = "author_name";
    /*public $author_name_bn  = "author_name_bn";
    public $author_name_en  = "author_name_en";*/
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