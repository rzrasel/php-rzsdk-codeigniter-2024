<?php
namespace App\Microservice\Schema\Domain\Model\Language;
?>
<?php
class LanguageEntity {
    public $id              = "id";
    public $name            = "name";
    public $iso_code_2      = "iso_code_2";
    public $iso_code_3      = "iso_code_3";
    public $slug            = "slug";
    public $created_date    = "created_date";
    public $modified_date   = "modified_date";
    public $created_by      = "created_by";
    public $modified_by     = "modified_by";

    public static function set(
        $id = null,
        $name = null,
        $isoCode2 = null,
        $isoCode3 = null,
        $slug = null,
        $createdDate = null,
        $modifiedDate = null,
        $createdBy = null,
        $modifiedBy = null,
    ): self {
        $dataModel = new self();
        $dataModel->id = $id;
        $dataModel->name = $name;
        $dataModel->iso_code_2 = $isoCode2;
        $dataModel->iso_code_3 = $isoCode3;
        $dataModel->slug = $slug;
        $dataModel->created_date = $createdDate;
        $dataModel->modified_date = $modifiedDate;
        $dataModel->created_by = $createdBy;
        $dataModel->modified_by = $modifiedBy;
        return $dataModel;
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