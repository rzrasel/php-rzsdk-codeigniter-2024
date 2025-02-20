<?php
namespace App\DatabaseSchema\Domain\Models;
?>
<?php
class ColumnKeyModel {
    public $id;
    public $mainColumn;
    public $keyType;
    public $referenceColumn;
    public $keyName;
    public $uniqueName;
    public $modifiedDate;
    public $createdDate;
    public $compositeKeyList = []; // One-to-many relationship with CompositeKey

    public function __construct(
        int $id = 0,
        int $mainColumn = 0,
        string $keyType = null,
        ?string $referenceColumn = null,
        ?string $keyName = null,
        ?string $uniqueName = null,
        ?string $modifiedDate = null,
        ?string $createdDate = null,
        ?string $compositeKeyList = null
    ) {
        $this->id = $id;
        $this->mainColumn = $mainColumn;
        $this->keyType = $keyType;
        $this->referenceColumn = $referenceColumn;
        $this->keyName = $keyName;
        $this->uniqueName = $uniqueName;
        $this->modifiedDate = $modifiedDate;
        $this->createdDate = $createdDate;
        $this->compositeKeyList = $compositeKeyList;
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