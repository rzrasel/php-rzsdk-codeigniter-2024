<?php
namespace App\DatabaseSchema\Data\Entities;
?>
<?php
class ColumnKey {
    public $id = "id";
    public $working_table = "working_table";
    public $main_column = "main_column";
    public $key_type = "key_type";
    public $reference_column = "reference_column";
    public $key_name = "key_name";
    public $unique_name = "unique_name";
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

    public function setVars() {
        $varList = $this->getVarList();
        foreach($varList as $var) {
            $this->{$var} = $var;
        }
    }
}
?>