<?php
namespace App\DatabaseSchema\Data\Entities;
?>
<?php
class ColumnData {
    public $table_id;
    public $id;
    public $column_name;
    public $data_type;
    public $is_nullable;
    public $default_value;
    public $column_comment;
    public $modified_date;
    public $created_date;

    public function getVarList() {
        $result = array_intersect_key(
            get_object_vars($this),
            get_mangled_object_vars($this)
        );
        return array_keys($result);
    }

    public function getVarListWithKey() {
        return array_intersect_key(
            get_object_vars($this),
            get_mangled_object_vars($this)
        );
    }
}
?>