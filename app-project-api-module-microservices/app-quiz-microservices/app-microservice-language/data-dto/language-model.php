<?php
namespace App\Microservice\Schema\Data\Model\Language;
?>
<?php
class LanguageModel {
    public $id;
    public $name;
    public $isoCode2;
    public $isoCode3;
    public $slug;
    public $modifiedDate;
    public $createdDate;
    public $modifiedBy;
    public $createdBy;

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
        $dataModel->isoCode2 = $isoCode2;
        $dataModel->isoCode3 = $isoCode3;
        $dataModel->slug = $slug;
        $dataModel->modifiedDate = $modifiedDate;
        $dataModel->createdDate = $createdDate;
        $dataModel->modifiedBy = $modifiedBy;
        $dataModel->createdBy = $createdBy;
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