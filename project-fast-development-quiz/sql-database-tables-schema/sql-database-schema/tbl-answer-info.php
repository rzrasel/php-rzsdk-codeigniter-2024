<?php
namespace RzSDK\Database\Schema;
?>
<?php
class TblAnswerInfo {
    public static $prefix = "tbl_";
    public static $table = "answer_info";
    //public static $foreignTable = TblSubjectInfo::tableWithPrefix();
    //
    public $question_id     = "question_id";
    public $answer_id       = "answer_id";
    public $answer_bn       = "answer_bn";
    public $answer_en       = "answer_en";
    public $is_correct      = "is_correct";
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