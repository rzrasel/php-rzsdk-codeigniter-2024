<?php
namespace RzSDK\Database\Schema;
?>
<?php
class TblQuestionInfo {
    public static $prefix = "tbl_";
    public static $table = "question_info";
    //public static $foreignTable = TblSubjectInfo::tableWithPrefix();
    //
    public $subject_id      = "subject_id";
    public $question_id     = "question_id";
    public $question_bn     = "question_bn";
    public $question_en     = "question_en";
    public $order           = "order";
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