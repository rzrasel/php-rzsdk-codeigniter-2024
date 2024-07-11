<?php
namespace RzSDK\Service\Adapter\User\Registration;
?>
<?php
/*require_once("../include.php");
require_once("include.php");*/
?>
<?php
use RzSDK\HTTPRequest\UserRegistrationRequest;
use RzSDK\Service\Listener\ServiceListener;
use RzSDK\Validation\BuildValidationRules;
use RzSDK\Log\DebugLog;
?>
<?php
class UserRegistrationRequestValidationService {
    private ServiceListener $serviceListener;

    public function __construct(ServiceListener $serviceListener) {
        $this->serviceListener = $serviceListener;
    }

    public function execute($requestDataSet) {
        $buildValidationRules = new BuildValidationRules();
        $userRegistrationRequest = new UserRegistrationRequest();
        $userRegistrationParamList = $userRegistrationRequest->getQuery();
        //DebugLog::log($requestDataSet);
        //DebugLog::log($userRegistrationParamList);
        foreach($userRegistrationParamList as $key => $value) {
            if(array_key_exists($key, $requestDataSet)) {
                $paramValue = $requestDataSet[$key];
                $userRegistrationRequest->$key = $paramValue;
                //
                //Execute and check validation rules
                $method = $key . "_rules";
                if(method_exists($userRegistrationRequest, $method)) {
                    $validationRules = $userRegistrationRequest->{$method}();
                    //DebugLog::log($validationRules);
                    $isValidated = $buildValidationRules
                        ->setRules($paramValue, $validationRules)
                        ->run();
                    if(!$isValidated["is_validate"]) {
                        $this->serviceListener->onError($requestDataSet, $isValidated["message"]);
                        return;
                    }
                }
            } else {
                $this->serviceListener->onError($requestDataSet, "Error! request parameter not matched out of type");
                return;
            }
        }
        $this->serviceListener->onSuccess($userRegistrationRequest, "Success! request validated");
    }
}
?>