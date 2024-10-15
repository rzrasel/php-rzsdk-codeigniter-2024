<?php
namespace RzSDK\Database\Quiz;
?>
<?php
class TblCharacterMapping {
    public static $prefix = "";
    public static $table = "character_mapping";
    //
    public $char_index_id   = "char_index_id";
    public $char_mapping_id = "char_mapping_id";
    public $str_char        = "str_char";
    public $ascii_char      = "ascii_char";
    public $char_ascii      = "char_ascii";
    public $hex_code        = "hex_code";
    public $u_plus_code     = "u_plus_code";
    public $u_code          = "u_code";
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