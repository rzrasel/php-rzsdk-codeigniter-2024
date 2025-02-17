<?php
namespace App\DatabaseSchema\Domain\Models;
?>
<?php
class TableDataModel {
    public int $schemaId;
    public int $id;
    public string $tableName;
    public ?string $columnPrefix;
    public ?string $tableComment;
    public string $modifiedDate;
    public string $createdDate;
    public array $columnData = []; // One-to-many relationship with ColumnData
    public function __construct(
        int $schemaId = 0,
        int $id = 0,
        string $tableName = null,
        ?string $tableComment = null,
        ?string $columnPrefix = null,
        ?string $modifiedDate = null,
        ?string $createdDate = null
    ) {
        $this->schemaId = $schemaId;
        $this->id = $id;
        $this->tableName = $tableName;
        $this->tableComment = $tableComment;
        $this->columnPrefix = $columnPrefix;
        $this->modifiedDate = $modifiedDate ?? date('Y-m-d H:i:s');
        $this->createdDate = $createdDate ?? date('Y-m-d H:i:s');
    }

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

    public static function getVarByValue(string $value, array $array): string|null {
        return array_search($value, $array, true) ?: null;
    }
}
?>