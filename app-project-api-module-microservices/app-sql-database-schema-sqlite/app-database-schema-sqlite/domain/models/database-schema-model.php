<?php
namespace App\DatabaseSchema\Domain\Models;
?>
<?php
class DatabaseSchemaModel {
    public $id;
    public $schemaName;
    public $schemaVersion;
    public $tablePrefix;
    public $databaseComment;
    public $modifiedDate;
    public $createdDate;
    public array $tableDataList = []; // One-to-many relationship with TableData
    public function __construct(
        int $id = 0,
        string $schemaName = "",
        ?string $schemaVersion = "",
        ?string $tablePrefix = "",
        ?string $databaseComment = "",
        string $modifiedDate = "",
        string $createdDate = "",
        ?array $tableDataList = []
    ) {
        $this->id = $id;
        $this->schemaName = $schemaName;
        $this->schemaVersion = $schemaVersion;
        $this->tablePrefix = $tablePrefix;
        $this->databaseComment = $databaseComment;
        $this->modifiedDate = $modifiedDate ?? date('Y-m-d H:i:s');
        $this->createdDate = $createdDate ?? date('Y-m-d H:i:s');
        $this->tableDataList = $tableDataList;
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