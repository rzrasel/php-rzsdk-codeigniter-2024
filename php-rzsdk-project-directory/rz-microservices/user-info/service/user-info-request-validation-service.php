<?php
namespace RzSDK\Service\Adapter\User\Info;
?>
<?php
/*require_once("../include.php");
require_once("include.php");*/
?>
<?php
use RzSDK\Service\Listener\ServiceListener;
use RzSDK\Validation\BuildValidationRules;
use RzSDK\HTTPRequest\UserInfoRequest;
use RzSDK\Log\DebugLog;
?>
<?php
class UserInfoRequestValidationService {
    private ServiceListener $serviceListener;

    public function __construct(ServiceListener $serviceListener) {
        $this->serviceListener = $serviceListener;
    }

    public function execute($requestDataSet) {
        $buildValidationRules = new BuildValidationRules();
        $userInfoRequest = new UserInfoRequest();
        $userInfoParamList = $userInfoRequest->getQuery();
        //DebugLog::log($requestDataSet);
        //DebugLog::log($userInfoParamList);
        //
        foreach($userInfoParamList as $key => $value) {
            if(array_key_exists($key, $requestDataSet)) {
                $paramValue = $requestDataSet[$key];
                $userInfoRequest->$key = $paramValue;
                //
                //Execute and check validation rules
                $method = $key . "_rules";
                if(method_exists($userInfoRequest, $method)) {
                    $validationRules = $userInfoRequest->{$method}();
                    //DebugLog::log($validationRules);
                    $isValidated = $buildValidationRules
                        ->setRules($paramValue, $validationRules)
                        ->run();
                    if(!$isValidated["is_validate"]) {
                        $this->serviceListener->onError($requestDataSet, $isValidated["message"] . "error code " . __LINE__);
                        return;
                    }
                }
            } else {
                $this->serviceListener->onError($requestDataSet, "Error! request parameter not matched out of type error code " . __LINE__);
                return;
            }
        }
        //
        $this->serviceListener->onSuccess($userInfoRequest, "Success! request validated");
    }
}
?>