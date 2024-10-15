<?php
namespace RzSDK\Database\Quiz;
?>
<?php
class TblQuizSubject {
    public static $prefix = "";
    public static $table = "quiz_subject";
    //
    public $lan_id          = "lan_id";
    public $subject_id      = "subject_id";
    public $subject_name    = "subject_name";
    public $slug            = "slug";
    public $subject_order   = "subject_order";
    public $status          = "status";
    public $is_quiz_mode    = "is_quiz_mode";
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