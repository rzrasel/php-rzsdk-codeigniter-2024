<?php
namespace App\DatabaseSchema\Domain\Models;
?>
<?php
class CompositeKeyModel {
    public $columnId;
    public $keyId;
    public $id;
    public $keyName;
    public $modifiedDate;
    public $createdDate;

    public function __construct(
        int $columnId = 0,
        int $keyId = 0,
        int $id = 0,
        ?string $keyName = null,
        ?\DateTime $modifiedDate = null,
        ?\DateTime $createdDate = null
    ) {
        $this->columnId = $columnId;
        $this->keyId = $keyId;
        $this->id = $id;
        $this->keyName = $keyName;
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

    public static function getVarByValue(string $value, array $array): string|null {
        return array_search($value, $array, true) ?: null;
    }
}
?>