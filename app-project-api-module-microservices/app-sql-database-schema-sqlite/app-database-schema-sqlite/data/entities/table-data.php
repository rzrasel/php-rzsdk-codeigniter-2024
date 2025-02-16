<?php
namespace App\DatabaseSchema\Data\Entities;
?>
<?php
class TableData {
    public int $schema_id;
    public int $id;
    public string $table_name;
    public ?string $column_prefix;
    public ?string $table_comment;
    public string $modified_date;
    public string $created_date;

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