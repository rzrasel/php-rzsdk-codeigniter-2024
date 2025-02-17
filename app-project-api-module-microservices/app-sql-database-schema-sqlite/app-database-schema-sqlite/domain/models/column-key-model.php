<?php
namespace App\DatabaseSchema\Domain\Models;
?>
<?php
class ColumnKeyModel {
    public $columnId;
    public $id;
    public $keyType;
    public $keyName;
    public $mainTable;
    public $referenceTable;
    public $referenceColumn;
    public $modifiedDate;
    public $createdDate;
    public array $compositeKey = []; // One-to-many relationship with CompositeKey

    public function __construct(
        int $columnId,
        int $id,
        string $keyType,
        ?string $keyName,
        string $mainTable,
        string $referenceTable,
        string $referenceColumn,
        ?string $modifiedDate,
        ?string $createdDate
    ) {
        $this->columnId = $columnId;
        $this->id = $id;
        $this->keyType = $keyType;
        $this->keyName = $keyName;
        $this->mainTable = $mainTable;
        $this->referenceTable = $referenceTable;
        $this->referenceColumn = $referenceColumn;
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