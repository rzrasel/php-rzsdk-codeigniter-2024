<?php
namespace RzSDK\Data\Validation\Helper;
?>
<?php
//use App\Microservice\Model\Request\Language\LanguageRequestData;
use RzSDK\Data\Validation\Build\BuildValidationRules;
?>
<?php
class ValidationHelper {
    public static function dataValidation($requestDataSet, $dataModelObject) {
        //$dataModelObject = new LanguageRequestData();
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
                "data_set" => $requestDataSet,
                "data_model" => $dataModelObject,
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
            "data_set" => $requestDataSet,
            "data_model" => $dataModelObject,
        );
    }
}
?>
