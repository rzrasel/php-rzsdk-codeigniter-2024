<?php
require_once("../include.php");
require_once("include.php");
?>
<?php
use RzSDK\Curl\Curl;
use RzSDK\Response\Response;
use RzSDK\Response\Info;
use RzSDK\Response\InfoType;
use RzSDK\Model\User\Login\UserLoginRequestModel;
use RzSDK\User\Login\UserLoginRegexValidation;
use RzSDK\User\Type\UserAuthType;
use function RzSDK\User\Type\getUserAuthTypeByValue;
use RzSDK\Database\SqliteConnection;
use RzSDK\Validation\BuildValidationRules;
use RzSDK\HTTPRequest\UserLoginRequest;
use RzSDK\Response\InfoTypeExtension;
use RzSDK\Log\DebugLog;

use function RzSDK\Response\getInfoTypeByValue;

?>
<?php
class UserLogin {
    public function __construct() {
        $this->execute();
    }

    public function execute() {
        if(!empty($_POST)) {
            $isValidated = $this->isValidated($_POST);
            if(!$isValidated["is_validate"]) {
                return;
            }
            $userLoginRequestModel = $isValidated["data_set"];
            //DebugLog::log($userRegiRequestModel);
            $postedDataSet = $userLoginRequestModel->toArrayKeyMapping($userLoginRequestModel);
            //DebugLog::log($postedDataSet);
            $enumValue = $userLoginRequestModel->authType;
            $userAuthType = getUserAuthTypeByValue($enumValue);

            if(!empty($userAuthType)) {
                if($userAuthType == UserAuthType::EMAIL) {
                    $this->loginByEmail($userLoginRequestModel, $postedDataSet);
                } else {
                    $this->response(null,
                        "Error! request parameter not matched out of type",
                        InfoType::ERROR,
                        $postedDataSet);
                }
            } else {
                $this->response(null,
                    "Error! request parameter not matched",
                    InfoType::ERROR,
                    $postedDataSet);
            }
            //$this->response(null, "Successful login completed", InfoType::SUCCESS, $dataModel);
        }
    }

    public function isValidated($requestDataSet) {
        //DebugLog::log($requestDataSet);
        $buildValidationRules = new BuildValidationRules();
        $userLoginRequest = new UserLoginRequest();
        $loginParamList = $userLoginRequest->getQuery();
        //DebugLog::log($loginParamList);
        $userLoginRequestModel = new UserLoginRequestModel();
        $keyMapping = $userLoginRequestModel->propertyKeyMapping();
        //DebugLog::log($keyMapping);
        $isValidated = true;
        foreach($loginParamList as $value) {
            //Extract requested values from $_POST
            if(array_key_exists($value, $requestDataSet)) {
                $paramValue = $requestDataSet[$value];
                $userLoginRequestModel->{$keyMapping[$value]} = $paramValue;
            } else {
                //Error array key not exist, return
                $this->response(null,
                    "Error! need to request by all parameter",
                    InfoType::ERROR,
                    $requestDataSet);
                $isValidated = false;
            }
            //Execute and check validation rules
            $method = $value . "_rules";
            if(method_exists($userLoginRequest, $method)) {
                $validationRules = $userLoginRequest->{$method}();
                //DebugLog::log($validationRules);
                $isValidated = $buildValidationRules->setRules($paramValue, $validationRules)
                    ->run();
                if(!$isValidated["is_validate"]) {
                    $this->response(null,
                        $isValidated["message"],
                        InfoType::ERROR,
                        $requestDataSet);
                    $isValidated = false;
                }
            }
        }
        //$returnValue = $userRegiRequestModel;
        //DebugLog::log($userRegiRequestModel);
        return array(
            "is_validate"   => $isValidated,
            "data_set"          => $userLoginRequestModel,
        );
    }

    private function loginByEmail(UserLoginRequestModel $userLoginRequestModel, array $postedDataSet) {
        if(!$this->isExistsUserInDatabase($userLoginRequestModel, $postedDataSet)) {
            $this->response(null, new Info("Error! Email or password not matched", InfoType::ERROR), $postedDataSet);
            return;
        }
    }

    private function isExistsUserInDatabase(UserLoginRequestModel $userLoginRequestModel, array $postedDataSet) {
        $url = dirname(ROOT_URL) . "/user-password-authentication/user-authentication.php";
        //
        $curl = new Curl($url);
        $result = $curl->exec(true, $postedDataSet) . "";
        $result = json_decode($result, true);
        //DebugLog::log($result);
        if(!is_array($result)) {
            return false;
        }
        if(!array_key_exists("body", $result)) {
            return false;
        }
        $responseData = json_decode($result["body"], true);
        //DebugLog::log($responseData);
        $bodyData = $responseData["body"];
        $infoData = $responseData["info"];
        //DebugLog::log($bodyData);
        //DebugLog::log($infoData);
        $enumValue = $infoData["type"];
        $infoType = InfoTypeExtension::getInfoTypeByValue($enumValue);

        if(!empty($infoType)) {
            if($infoType == InfoType::SUCCESS) {
                $password = $userLoginRequestModel->password;
                $hash = $bodyData["password"];
                /*if(password_verify($password, $hash)) {
                    $bodyData["password"] = $password;
                    $this->response($bodyData, new Info("Successfully logged in", InfoType::SUCCESS), $postedDataSet);
                    return true;
                }*/
                $this->response($bodyData, new Info("Successfully logged in", InfoType::SUCCESS), $postedDataSet);
                return true;
            } else if($infoType == InfoType::INFO) {
                $this->response(array("redirect" => true, "page" => "registration"), new Info("Error! Email or password not matched", InfoType::ERROR), $postedDataSet);
                return true;
            }
        }
        return false;
    }

    private function response($body, Info $info, $parameter = null) {
        $response = new Response();
        $response->body         = $body;
        $response->info         = $info;
        $response->parameter    = $parameter;
        echo $response->toJson();
    }

    /*public function executeOld() {
        if(!empty($_POST)) {
            //DebugLog::log($_POST);
            $isValidated = $this->isValidated($_POST);
            if(!$isValidated["is_validate"]) {
                return;
            }
            $userRegiRequestModel = new UserRegistrationRequestModel();
            $userRegiRequestModel->agentType = $_POST[$userRegiRequestModel->agentType];
            $userRegiRequestModel->authType = $_POST[$userRegiRequestModel->authType];
            if(array_key_exists($userRegiRequestModel->deviceType, $_POST)) {
                $userRegiRequestModel->deviceType = $_POST[$userRegiRequestModel->deviceType];
            }
            $userRegiRequestModel->email = $_POST[$userRegiRequestModel->email];
            $userRegiRequestModel->password = $_POST[$userRegiRequestModel->password];
            $dataModel = $userRegiRequestModel->toArrayKeyMapping($userRegiRequestModel);

            $enumValue = $userRegiRequestModel->authType;
            $userAuthType = getUserAuthTypeByValue($enumValue);

            if(!empty($userAuthType)) {
                if($userAuthType == UserAuthType::EMAIL) {
                    $this->registrationByEmail($userRegiRequestModel);
                } else {
                    $this->response(null, new Info("Error! request parameter not matched out of type", InfoType::ERROR), $dataModel);
                }
            } else {
                $this->response(null, new Info("Error! request parameter not matched", InfoType::ERROR), $dataModel);
            }

            //$this->response(null, new Info("Successful registration completed", InfoType::SUCCESS), $dataModel);
        }
    }*/

    /*private function registrationByEmail(UserRegistrationRequestModel $userRegiRequestModel) {
        $dataModel = $userRegiRequestModel->toArrayKeyMapping($userRegiRequestModel);
        if(!$this->regexValidation($userRegiRequestModel)) {
            return;
        }
        if(!$this->haveDbUser($userRegiRequestModel)) {
            $this->response(null, new Info("Error! Email or password not matched", InfoType::ERROR), $dataModel);
            return;
        }
        //$this->userResitration($userRegiRequestModel);
    }*/

    /*private function regexValidation(UserRegistrationRequestModel $userRegiRequestModel) {
        $userLoginRegexValidation = new UserLoginRegexValidation();
        return $userLoginRegexValidation->execute($userRegiRequestModel);
    }*/
}
?>
<?php
$userLogin = new UserLogin();
?>