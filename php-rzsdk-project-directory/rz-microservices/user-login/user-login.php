<?php
require_once("../include.php");
require_once("include.php");
?>
<?php
use RzSDK\Curl\Curl;
use RzSDK\Response\Response;
use RzSDK\Response\Info;
use RzSDK\Response\InfoType;
use RzSDK\Model\User\Login\UserRegistrationRequestModel;
use RzSDK\User\Login\UserLoginRegexValidation;
use RzSDK\User\Type\UserAuthType;
use function RzSDK\User\Type\getUserAuthTypeByValue;
use RzSDK\Database\SqliteConnection;
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
            //DebugLog::log($_POST);
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
    }

    private function registrationByEmail(UserRegistrationRequestModel $userRegiRequestModel) {
        $dataModel = $userRegiRequestModel->toArrayKeyMapping($userRegiRequestModel);
        if(!$this->regexValidation($userRegiRequestModel)) {
            return;
        }
        if(!$this->haveDbUser($userRegiRequestModel)) {
            $this->response(null, new Info("Error! Email or password not matched", InfoType::ERROR), $dataModel);
            return;
        }
        //$this->userResitration($userRegiRequestModel);
    }

    private function regexValidation(UserRegistrationRequestModel $userRegiRequestModel) {
        $userLoginRegexValidation = new UserLoginRegexValidation();
        return $userLoginRegexValidation->execute($userRegiRequestModel);
    }

    private function haveDbUser(UserRegistrationRequestModel $userRegiRequestModel) {
        $url = dirname(ROOT_URL) . "/user-authentication/user-authentication.php";
        $dataModel = $userRegiRequestModel->toArrayKeyMapping($userRegiRequestModel);
        //
        $curl = new Curl($url);
        $result = $curl->exec(true, $dataModel) . "";
        $result = json_decode($result, true);
        //DebugLog::log($result);
        $responseData = json_decode($result["body"], true);
        //DebugLog::log($responseData);
        $bodyData = $responseData["body"];
        $infoData = $responseData["info"];
        //DebugLog::log($bodyData);
        //DebugLog::log($infoData);
        $enumValue = $infoData["type"];
        $infoType = getInfoTypeByValue($enumValue);

        if(!empty($infoType)) {
            if($infoType == InfoType::SUCCESS) {
                $password = $userRegiRequestModel->password;
                $hash = $bodyData["password"];
                if(password_verify($password, $hash)) {
                    $bodyData["password"] = $password;
                    $this->response($bodyData, new Info("Successfully logged in", InfoType::SUCCESS), $dataModel);
                    return true;
                }
            } else if($infoType == InfoType::INFO) {
                $this->response(array("redirect" => true, "page" => "registration"), new Info("Error! Email or password not matched", InfoType::ERROR), $dataModel);
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
}
?>
<?php
$userLogin = new UserLogin();
?>