<?php
namespace RzSDK\Service\Adapter\User\Authentication\Token;
?>
<?php
/*require_once("../include.php");
require_once("include.php");*/
?>
<?php
use RzSDK\Service\Listener\ServiceListener;
use RzSDK\Validation\BuildValidationRules;
use RzSDK\HTTPRequest\UserAuthTokenAuthenticationRequest;
use RzSDK\Model\User\Authentication\Token\UserAuthTokenAuthenticationRequestModel;
use RzSDK\Log\DebugLog;
?>
<?php
class UserAuthTokenAuthenticationRequestValidationService {
    private ServiceListener $serviceListener;

    public function __construct(ServiceListener $serviceListener) {
        $this->serviceListener = $serviceListener;
    }

    public function execute($requestDataSet) {
        $buildValidationRules = new BuildValidationRules();
        $userAuthTokenAuthRequest = new UserAuthTokenAuthenticationRequest();
        $userAuthTokenAuthParamList = $userAuthTokenAuthRequest->getQuery();
        foreach($userAuthTokenAuthParamList as $key => $value) {
            if(array_key_exists($key, $requestDataSet)) {
                $paramValue = $requestDataSet[$key];
                $userAuthTokenAuthRequest->$key = $paramValue;
                //
                //Execute and check validation rules
                $method = $key . "_rules";
                if(method_exists($userAuthTokenAuthRequest, $method)) {
                    $validationRules = $userAuthTokenAuthRequest->{$method}();
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
        $this->serviceListener->onSuccess($userAuthTokenAuthRequest, "Success! request validated");
    }
}
?>