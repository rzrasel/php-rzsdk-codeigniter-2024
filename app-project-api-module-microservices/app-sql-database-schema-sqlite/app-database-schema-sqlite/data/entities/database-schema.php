<?php
namespace App\DatabaseSchema\Data\Entities;
?>
<?php
class DatabaseSchema {
    public $id = "id";
    public $schema_name = "schema_name";
    public $schema_version = "schema_version";
    public $table_prefix = "table_prefix";
    public $database_comment = "database_comment";
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
}
?>