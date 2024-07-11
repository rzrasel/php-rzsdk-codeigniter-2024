<?php
require_once("../include.php");
require_once("include.php");
?>
<?php
use RzSDK\HTTPResponse\LaunchResponse;
use RzSDK\Response\InfoType;
use RzSDK\Model\User\Authentication\Token\UserAuthTokenAuthenticationRequestModel;
use RzSDK\HTTPRequest\UserAuthTokenAuthenticationRequest;
use RzSDK\Validation\BuildValidationRules;
use RzSDK\DatabaseSpace\UserLoginAuthLogTable;
use RzSDK\Service\Listener\ServiceListener;
use RzSDK\Service\Adapter\User\Authentication\Token\UserAuthTokenAuthenticationRequestValidationService;
use RzSDK\Model\User\Authentication\Token\UserAuthTokenAuthenticationResponseModel;
use RzSDK\Service\Adapter\User\Authentication\Token\UserAuthTokenAuthenticationDatabaseValidationService;
use RzSDK\Log\DebugLog;
?>
<?php
class UserAuthTokenAuthenticationToken {
    public UserAuthTokenAuthenticationRequestModel $userAuthTokenAuthRequestModel;
    private UserAuthTokenAuthenticationRequest $userAuthTokenAuthRequest;
    private $postedDataSet;

    public function __construct(){
        $this->execute();
    }

    public function execute() {
        if(!empty($_POST)) {
            //DebugLog::log($_POST);
            //
            $innerInstance = new class($this) implements ServiceListener {
                private UserAuthTokenAuthenticationToken $outerInstance;

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
                    $this->outerInstance->userTokenDatabaseValidation($dataSet);
                }
            };
            $userAuthTokenAuthRequestValidationService = new UserAuthTokenAuthenticationRequestValidationService($innerInstance);
            $userAuthTokenAuthRequestValidationService->execute($_POST);
            //
            //$this->response(null, "Successful login completed", InfoType::SUCCESS, $_POST);
        }
    }

    public function userTokenDatabaseValidation($dataSet) {
        //DebugLog::log($dataSet);
        $this->userAuthTokenAuthRequest = $dataSet;
        //DebugLog::log($userRegiRequestModel);
        $postedDataSet = $this->userAuthTokenAuthRequest->getPropertyKeyValue();
        //DebugLog::log($postedDataSet);
        (new UserAuthTokenAuthenticationDatabaseValidationService(
            new class($this) implements ServiceListener {
                private UserAuthTokenAuthenticationToken $outerInstance;

                // Constructor to receive outer instance
                public function __construct($outerInstance) {
                    $this->outerInstance = $outerInstance;
                }

                public function onError($dataSet, $message) {
                    $this->outerInstance->response(null, $message, InfoType::ERROR, $dataSet);
                }

                function onSuccess($dataSet, $message) {
                    //DebugLog::log($message);
                    $this->outerInstance->httpResponse($dataSet);
                }
            }
        ))->execute($this->userAuthTokenAuthRequest, $postedDataSet);
    }

    public function httpResponse(UserLoginAuthLogTable $userLoginAuthLogTable) {
        //DebugLog::log($userLoginAuthLogTable);
        $UserAuthTokenAuthRequestModel = new UserAuthTokenAuthenticationResponseModel();
        /*$UserAuthTokenAuthRequestModel->device_type = $this->userAuthTokenAuthRequest->device_type;
        $UserAuthTokenAuthRequestModel->auth_type = $this->userAuthTokenAuthRequest->auth_type;
        $UserAuthTokenAuthRequestModel->agent_type = $this->userAuthTokenAuthRequest->agent_type;*/
        $UserAuthTokenAuthRequestModel->user_id = $userLoginAuthLogTable->user_id;
        $UserAuthTokenAuthRequestModel->user_auth_token = $userLoginAuthLogTable->auth_token;
        //DebugLog::log($userAuthTokenAuthRequest);
        $this->response($UserAuthTokenAuthRequestModel->getQuery(), "Successful user authentication.", InfoType::SUCCESS, $this->userAuthTokenAuthRequest->getPropertyKeyValue());
    }

    public function response($body, $message, InfoType $infoType, $parameter = null) {
        $launchResponse = new LaunchResponse();
        $launchResponse->setBody($body)
            ->setInfo($message, $infoType)
            ->setParameter($parameter)
            ->execute();
    }

    /*public function isValidated($requestDataSet) {
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
    }*/
}
?>
<?php
$userAuthTokenAuthenticationToken = new UserAuthTokenAuthenticationToken();
?>
