<?php
namespace App\Microservice\Schema\Domain\Model\Subject;
?>
<?php
class SubjectEntity {
    public $language_id         = "language_id";
    public $id                  = "id";
    public $name                = "name";
    public $description         = "description";
    public $subject_code        = "subject_code";
    public $subject_identity    = "subject_identity";
    public $slug                = "slug";
    public $created_date        = "created_date";
    public $modified_date       = "modified_date";
    public $created_by          = "created_by";
    public $modified_by         = "modified_by";

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
        $dataModel->language_id = $languageId;
        $dataModel->id = $id;
        $dataModel->name = $name;
        $dataModel->description = $description;
        $dataModel->subject_code = $subjectCode;
        $dataModel->subject_identity = $subjectIdentity;
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