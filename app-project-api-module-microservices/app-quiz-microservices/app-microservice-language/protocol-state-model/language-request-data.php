<?php
namespace App\Microservice\Protocol\State\Model\Request\Language;
?>
<?php
use RzSDK\Data\Validation\Preparation\PrepareValidationRules;
use RzSDK\Data\Validation\Build\BuildValidationRules;
?>
<?php
class LanguageRequestData {
    public $database_type;
    public $language_name = "language_name";
    public $iso_code_2 = "iso_code_2";
    public $iso_code_3  = "iso_code_3";
    public $slug = "slug";

    public function __construct() {}

    /*public function __construct($database_type = null, $language_name = null, $iso_code_2 = null, $iso_code_3 = null, $slug = null) {
        $this->database_type = $database_type;
        $this->language_name = $language_name;
        $this->iso_code_2 = $iso_code_2;
        $this->iso_code_3 = $iso_code_3;
        $this->slug = $slug;
    }*/
    public static function set(
        $database_type = null,
        $language_name = null,
        $iso_code_2 = null,
        $iso_code_3 = null,
        $slug = null,
    ): self {
        $dataModel = new self();
        $dataModel->database_type = $database_type;
        $dataModel->language_name = $language_name;
        $dataModel->iso_code_2 = $iso_code_2;
        $dataModel->iso_code_3 = $iso_code_3;
        $dataModel->slug = $slug;
        return $dataModel;
    }

    public function language_name_rules() {
        $rules = new PrepareValidationRules();
        return array(
            $rules->notNullRule("Language name can not be null"),
            $rules->minLengthRule(2, "Language name length must be at least 2 characters"),
            $rules->maxLengthRule(255, "Language name length must be less than 255 characters"),
        );
    }

    public function iso_code_2_rules() {
        $rules = new PrepareValidationRules();
        return array(
            $rules->notNullRule("ISO code 2 can not be null"),
            $rules->minLengthRule(2, "ISO code 2 length must be at least 2 characters"),
            $rules->maxLengthRule(255, "ISO code 2 length must be less than 255 characters"),
        );
    }

    public function iso_code_3_rules() {
        $rules = new PrepareValidationRules();
        return array(
            $rules->notNullRule("ISO code 3 can not be null"),
            $rules->minLengthRule(3, "ISO code 3 length must be at least 2 characters"),
            $rules->maxLengthRule(255, "ISO code 3 length must be less than 255 characters"),
        );
    }

    public function slug_rules() {
        $rules = new PrepareValidationRules();
        return array(
            $rules->notNullRule("Slug can not be null"),
            $rules->minLengthRule(2, "Slug length must be at least 2 characters"),
            $rules->maxLengthRule(255, "Slug length must be less than 255 characters"),
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

    /*public static function dataValidation($requestDataSet) {
        $dataModelObject = new LanguageRequestData();
        $modelParamList = $dataModelObject->getVarList();
        $isValidated = true;
        $message = array();
        $keyExistsCounter = 0;

        //|Check for missing required parameters|----------------|
        foreach($modelParamList as $key => $value) {
            $objVarValue = $dataModelObject->{$value};
            $dataModelObject->{$value} = null;
            if(!array_key_exists($value, $requestDataSet)) {
                if(!empty($objVarValue)) {
                    $keyExistsCounter++;
                    $isValidated = false;
                    $message[] = "{$keyExistsCounter}) {$value} is required, can't be empty";
                }
            } else {
                $dataModelObject->{$value} = $requestDataSet[$value];
            }
        }

        //|If there are missing fields, return early|------------|
        if(!$isValidated) {
            return array(
                "is_validate" => $isValidated,
                "message" => implode(", ", $message),
                "data" => $requestDataSet,
            );
        }
        $buildValidationRules = new BuildValidationRules();

        //|Validate each field using respective validation methods
        foreach($modelParamList as $key => $value) {
            //
            //$requestDataValue = $requestDataSet[$value];
            $requestDataValue = $dataModelObject->{$value};
            $method = $value . "_rules";
            //echo $method;
            if(method_exists($dataModelObject, $method)) {
                //echo $method;
                $validationRules = $dataModelObject->{$method}();
                $validationData = $buildValidationRules->setRules($requestDataValue, $validationRules)
                    ->run();
                //
                //echo json_encode($validationData);
                //$isValidated = $isValidated["is_validate"];

                //|If validation fails, append message|----------|
                if(!$validationData["is_validate"]) {
                    $isValidated = false;
                    $message[] = $validationData["message"];
                }
            }
        }
        return array(
            "is_validate" => $isValidated,
            "message" => implode(", ", $message),
            "data" => $dataModelObject,
        );
    }*/
}
?>
