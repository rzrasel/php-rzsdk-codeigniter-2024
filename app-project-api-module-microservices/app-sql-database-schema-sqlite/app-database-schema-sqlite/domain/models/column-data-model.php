<?php
namespace App\DatabaseSchema\Domain\Models;
?>
<?php
class ColumnDataModel {
    public $tableId;
    public $id;
    //public $uniqueName;
    public $columnOrder;
    public $columnName;
    public $dataType;
    public $isNullable;
    public $haveDefault;
    public $defaultValue;
    public $columnComment;
    public $modifiedDate;
    public $createdDate;
    public $columnKeyList = [];

    public function __construct(
        $tableId = null,
        //$uniqueName = null,
        $columnOrder = 1,
        $columnName = null,
        $dataType = null,
        $isNullable = null,
        $haveDefault = null,
        $defaultValue = null,
        $columnComment = null,
        $modifiedDate = null,
        $createdDate = null,
        $columnKeyList = null
    ) {
        $this->tableId = $tableId;
        //$this->uniqueName = $uniqueName;
        $this->columnOrder = $columnOrder;
        $this->columnName = $columnName;
        $this->dataType = $dataType;
        $this->isNullable = $isNullable;
        $this->haveDefault = $haveDefault;
        $this->defaultValue = $defaultValue;
        $this->columnComment = $columnComment;
        $this->modifiedDate = $modifiedDate;
        $this->createdDate = $createdDate;
        $this->columnKeyList = $columnKeyList;
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

    public function getIdByName(string $uniqueName): int|bool {
        if ($this->uniqueName === $uniqueName) {
            return $this->id;
        }
        return false;
    }

    public static function getStaticIdByName(string $uniqueName, ColumnDataModel $columnData): int|bool {
        if ($columnData->uniqueName === $uniqueName) {
            return $columnData->id;
        }
        return false;
    }

    public static function getIdByNameFilter(string $uniqueName, array $columnDataList): int|bool {
        $filtered = array_filter($columnDataList, fn($table) => $table->uniqueName === $uniqueName);
        return $filtered ? array_values($filtered)[0]->id : false;
    }
}
?>