<?php
namespace App\Microservice\Schema\Data\Model\Subject;
?>
<?php
class SubjectModel {
    public $languageId;
    public $id;
    public $name;
    public $description;
    public $subjectCode;
    public $subjectIdentity;
    public $slug;
    public $createdDate;
    public $modifiedDate;
    public $createdBy;
    public $modifiedBy;

    public static function set(
        $languageId = null,
        $id = null,
        $name = null,
        $description = null,
        $subjectCode = null,
        $subjectIdentity = null,
        $slug = null,
        $createdDate = null,
        $modifiedDate = null,
        $createdBy = null,
        $modifiedBy = null,
    ): self {
        $dataModel = new self();
        $dataModel->languageId = $languageId;
        $dataModel->id = $id;
        $dataModel->name = $name;
        $dataModel->description = $description;
        $dataModel->subjectCode = $subjectCode;
        $dataModel->subjectIdentity = $subjectIdentity;
        $dataModel->slug = $slug;
        $dataModel->createdDate = $createdDate;
        $dataModel->modifiedDate = $modifiedDate;
        $dataModel->createdBy = $createdBy;
        $dataModel->modifiedBy = $modifiedBy;
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