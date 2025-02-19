<?php
namespace App\DatabaseSchema\Domain\Models;
?>
<?php
class TableDataModel {
    public $schemaId;
    public $id;
    //public $uniqueName;
    public $tableName;
    public $columnPrefix;
    public $tableComment;
    public $modifiedDate;
    public $createdDate;
    public array $columnDataList = []; // One-to-many relationship with ColumnData
    public function __construct(
        $schemaId = 0,
        $id = 0,
        //$uniqueName = null,
        ?string $tableName = null,
        ?string $tableComment = null,
        ?string $columnPrefix = null,
        ?string $modifiedDate = null,
        ?string $createdDate = null,
        ?array $columnDataList = []
    ) {
        $this->schemaId = $schemaId;
        $this->id = $id;
        //$this->uniqueName = $uniqueName;
        $this->tableName = $tableName;
        $this->tableComment = $tableComment;
        $this->columnPrefix = $columnPrefix;
        $this->modifiedDate = $modifiedDate ?? date('Y-m-d H:i:s');
        $this->createdDate = $createdDate ?? date('Y-m-d H:i:s');
        $this->columnDataList = $columnDataList;
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

    public function getIdByName(string $name): int|bool {
        if ($this->tableName === $name) {
            return $this->id;
        }
        return false;
    }

    public static function getStaticIdByName(string $name, TableDataModel $tableData): int|bool {
        if ($tableData->tableName === $name) {
            return $tableData->id;
        }
        return false;
    }

    public function traverse(callable $callback): string {
        return $callback($this);
    }
}
?>