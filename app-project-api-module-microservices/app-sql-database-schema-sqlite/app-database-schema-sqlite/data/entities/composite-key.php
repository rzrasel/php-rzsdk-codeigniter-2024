<?php
namespace App\DatabaseSchema\Data\Entities;
?>
<?php
class CompositeKey {
    public $key_id = "key_id";
    public $id = "id";
    public $primary_column = "primary_column";
    public $composite_column = "composite_column";
    public $key_name = "key_name";
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