<?php
namespace App\Microservice\Protocol\State\Model\Request\Subject;
?>
<?php
use RzSDK\Data\Validation\Preparation\PrepareValidationRules;
use RzSDK\Data\Validation\Build\BuildValidationRules;
?>
<?php
class SubjectRequestData {
    public $language_id = "language_id";
    public $subject_name = "subject_name";
    public $description;
    public $subject_code = "subject_code";
    public $subject_identity = "subject_identity";
    public $slug = "slug";

    public function __construct() {}

    public function language_id_rules() {
        $rules = new PrepareValidationRules();
        return array(
            $rules->notNullRule("Language id can not be null"),
        );
    }

    public function subject_name_rules() {
        $rules = new PrepareValidationRules();
        return array(
            $rules->notNullRule("Subject name can not be null"),
            $rules->minLengthRule(2, "Subject name length must be at least 2 characters"),
            $rules->maxLengthRule(255, "Subject name length must be less than 255 characters"),
        );
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