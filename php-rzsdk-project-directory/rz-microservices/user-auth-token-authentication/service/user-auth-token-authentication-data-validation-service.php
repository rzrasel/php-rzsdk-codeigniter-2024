<?php
namespace RzSDK\Service\Adapter;
?>
<?php
/*require_once("../include.php");
require_once("include.php");*/
?>
<?php
use RzSDK\Service\Listener\ServiceListener;
use RzSDK\Validation\BuildValidationRules;
use RzSDK\HTTPRequest\UserAuthTokenAuthenticationRequest;
use RzSDK\Model\User\Authentication\UserAuthTokenAuthenticationRequestModel;
use RzSDK\Log\DebugLog;
?>
<?php
class UserAuthTokenAuthenticationDataValidationService {
    private ServiceListener $serviceListener;

    public function __construct(ServiceListener $serviceListener) {
        if($serviceListener != null) {
            $this->serviceListener = $serviceListener;
        }
    }

    public function execute($requestDataSet) {
        $buildValidationRules = new BuildValidationRules();
        $userAuthTokenAuthRequest = new UserAuthTokenAuthenticationRequest();
        //$userAuthTokenAuthRequestModel = new UserAuthTokenAuthenticationRequestModel();
        $userAuthTokenAuthParamList = $userAuthTokenAuthRequest->getQuery();
        $isValidated = true;
        foreach($userAuthTokenAuthParamList as $key => $value) {
            //$key = $key . "d";
            //$paramValue = "";
            if(array_key_exists($key, $requestDataSet)) {
                //$paramValue = $requestDataSet[$key];
                $userAuthTokenAuthRequest->$key = $requestDataSet[$key];
                $isValidated = $this->isValidateRules($userAuthTokenAuthRequest, $buildValidationRules, $key, $value);
                //DebugLog::log($isValidated);
                if(!$isValidated["is_validate"]) {
                    $this->serviceListener->onError($requestDataSet, $isValidated["data_set"]);
                    return;
                }
            } else {
                $this->serviceListener->onError($requestDataSet, "Error! request parameter not matched out of type");
                return;
            }
            //
        }
        $this->serviceListener->onSuccess($userAuthTokenAuthRequest, "Success! request validated");
    }

    private function isValidateRules(UserAuthTokenAuthenticationRequest $userAuthRequest, BuildValidationRules $buildValidationRules, $ruleKey, $ruleValue) {
        //Execute and check validation rules
        $method = $ruleKey . "_rules";
        //DebugLog::log($method);
        if(method_exists($userAuthRequest, $method)) {
            $validationRules = $userAuthRequest->{$method}();
            //DebugLog::log($validationRules);
            $isValidated = $buildValidationRules
                ->setRules($ruleKey, $validationRules)
                ->run();
            if(!$isValidated["is_validate"]) {
                return array(
                    "is_validate"   => false,
                    "data_set"          => $isValidated["message"],
                );
            }
        }
        return array(
            "is_validate"   => true,
            "data_set"          => null,
        );
    }
}
?>