<?php
namespace App\DatabaseSchema\Data\Entities;
?>
<?php
class TableData {
    public $schema_id;
    public $id;
    public $table_name;
    public $column_prefix;
    public $table_comment;
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