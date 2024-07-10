<?php
require_once("../include.php");
require_once("include.php");
?>
<?php
use RzSDK\HTTPResponse\LaunchResponse;
use RzSDK\Response\InfoType;
use RzSDK\Model\User\Authentication\UserAuthTokenAuthenticationRequestModel;
use RzSDK\HTTPRequest\UserAuthTokenAuthenticationRequest;
use RzSDK\Validation\BuildValidationRules;
use RzSDK\Service\Listener\ServiceListener;
use RzSDK\Service\Adapter\UserAuthTokenAuthenticationDataValidationService;
use RzSDK\Log\DebugLog;
?>
<?php
class UserAuthTokenAuthenticationToken {
    public UserAuthTokenAuthenticationRequestModel $userAuthTokenAuthRequestModel;
    private $postedDataSet;

    public function __construct(){
        $this->execute();
    }

    public function execute() {
        if(!empty($_POST)) {
            //DebugLog::log($_POST);
            $this->userAuthTokenAuthRequestModel = new UserAuthTokenAuthenticationRequestModel();
            /*$isValidated = $this->isValidated($_POST);
            if(!$isValidated["is_validate"]) {
                $this->response(null,
                    "Error! need to request by all parameter",
                    InfoType::ERROR,
                    $_POST);
                return;
            }
            $this->userAuthTokenAuthRequestModel = $isValidated["data_set"];
            //DebugLog::log($userRegiRequestModel);
            $postedDataSet = $this->userAuthTokenAuthRequestModel->objectUserAuthTokenAuthRequest->getPropertyKeyValue();
            DebugLog::log($postedDataSet);*/
            //
            $innerInstance = new class($this) implements ServiceListener {
                private $outerInstance;

                // Constructor to receive outer instance
                public function __construct($outerInstance) {
                    $this->outerInstance = $outerInstance;
                }

                public function onError($dataSet, $message) {
                    $this->outerInstance->response(null, $message, InfoType::ERROR, $dataSet);
                }

                function onSuccess($dataSet, $message) {
                    /*DebugLog::log($dataSet);
                    DebugLog::log($message);*/
                    //$this->outerInstance->userAuthTokenAuthRequestModel->objectUserAuthTokenAuthRequest = $dataSet;
                    $this->outerInstance->test($dataSet);
                }
            };
            $userAuthTokenAuthDataValidationService = new UserAuthTokenAuthenticationDataValidationService($innerInstance);
            $userAuthTokenAuthDataValidationService->execute($_POST);
            //DebugLog::log($innerInstance->sayHello("hi this is a value"));
            //
            //$this->response(null, "Successful login completed", InfoType::SUCCESS, $postedDataSet);
        }
    }

    public function test($dataSet) {
        //DebugLog::log($dataSet);
        //$this->userAuthTokenAuthRequestModel->objectUserAuthTokenAuthRequest = $this->userAuthTokenAuthRequestModel->toTypeCasting($dataSet);
        $this->userAuthTokenAuthRequestModel->objectUserAuthTokenAuthRequest = $dataSet;
        //DebugLog::log($userRegiRequestModel);
        $postedDataSet = $this->userAuthTokenAuthRequestModel->objectUserAuthTokenAuthRequest->getPropertyKeyValue();
        DebugLog::log($postedDataSet);
    }

    public function isValidated($requestDataSet) {
        //DebugLog::log($requestDataSet);
        $buildValidationRules = new BuildValidationRules();
        $userAuthTokenAuthRequest = new UserAuthTokenAuthenticationRequest();
        $userAuthTokenAuthParamList = $userAuthTokenAuthRequest->getQuery();
        $this->userAuthTokenAuthRequestModel = new UserAuthTokenAuthenticationRequestModel();
        $isValidated = true;
        foreach($userAuthTokenAuthParamList as $key => $value) {
            $paramValue = "";
            if(array_key_exists($key, $requestDataSet)) {
                $paramValue = $requestDataSet[$key];
                $userAuthTokenAuthRequest->$key = $requestDataSet[$key];
            } else {
                $this->response(null, "Error! request parameter not matched out of type", InfoType::ERROR, $requestDataSet);
                $isValidated = false;
                return array(
                    "is_validate"   => $isValidated,
                    "data_set"          => null,
                );
            }
            //Execute and check validation rules
            $method = $value . "_rules";
            if(method_exists($userAuthTokenAuthRequest, $method)) {
                $validationRules = $userAuthTokenAuthRequest->{$method}();
                //DebugLog::log($validationRules);
                $isValidated = $buildValidationRules->setRules($paramValue, $validationRules)
                    ->run();
                if(!$isValidated["is_validate"]) {
                    $this->response(null,
                        $isValidated["message"],
                        InfoType::ERROR,
                        $requestDataSet);
                    $isValidated = false;
                    return array(
                        "is_validate"   => $isValidated,
                        "data_set"          => null,
                    );
                }
            }
        }
        $this->userAuthTokenAuthRequestModel->objectUserAuthTokenAuthRequest = $userAuthTokenAuthRequest;
        //$returnValue = $userAuthTokenAuthRequest;
        //DebugLog::log($userAuthTokenAuthRequest);
        return array(
            "is_validate"   => $isValidated,
            "data_set"          => $this->userAuthTokenAuthRequestModel,
        );
    }

    public function response($body, $message, InfoType $infoType, $parameter = null) {
        $launchResponse = new LaunchResponse();
        $launchResponse->setBody($body)
            ->setInfo($message, $infoType)
            ->setParameter($parameter)
            ->execute();
    }
}
?>
<?php
$userAuthTokenAuthenticationToken = new UserAuthTokenAuthenticationToken();
?>
