<?php
namespace App\DatabaseSchema\Domain\Models;
?>
<?php
class ColumnDataModel {
    public $tableId;
    public $id;
    public $columnName;
    public $dataType;
    public $isNullable;
    public $defaultValue;
    public $columnComment;
    public $modifiedDate;
    public $createdDate;
    public array $columnKey = []; // One-to-many relationship with ColumnKey

    public function __construct(
        $tableId,
        $columnName,
        $dataType,
        $isNullable,
        $defaultValue,
        $columnComment,
        $modifiedDate,
        $createdDate
    ) {
        $this->tableId = $tableId;
        $this->columnName = $columnName;
        $this->dataType = $dataType;
        $this->isNullable = $isNullable;
        $this->defaultValue = $defaultValue;
        $this->columnComment = $columnComment;
        $this->modifiedDate = $modifiedDate;
        $this->createdDate = $createdDate;
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
}
?>