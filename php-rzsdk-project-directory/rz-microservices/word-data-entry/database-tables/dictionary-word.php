<?php
namespace RzSDK\Database\Word;
?>
<?php
class DictionaryWord {
    public static $prefix = "";
    public static $table = "dictionary_word";
    //
    public $lan_id         = "lan_id";
    public $word_id         = "word_id";
    public $word            = "word";
    public $pronunciation   = "pronunciation";
    public $accent_us       = "accent_us";
    public $accent_uk       = "accent_uk";
    public $parts_of_speech = "parts_of_speech";
    public $syllable        = "syllable";
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