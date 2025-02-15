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
}
?>