<?php
namespace App\DatabaseSchema\Data\Entities;
?>
<?php
class ColumnData {
    public $table_id = "table_id";
    public $id = "id";
    //public $unique_name = "unique_name";
    public $column_order = "column_order";
    public $column_name = "column_name";
    public $data_type = "data_type";
    public $is_nullable = "is_nullable";
    public $have_default = "have_default";
    public $default_value = "default_value";
    public $column_comment = "column_comment";
    public $modified_date = "modified_date";
    public $created_date = "created_date";

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

    public function setVars() {
        $varList = $this->getVarList();
        foreach($varList as $var) {
            $this->{$var} = $var;
        }
    }
}
?>