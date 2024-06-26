<?php
require_once("../include.php");
require_once("include.php");
?>
<?php
use RzSDK\Curl\Curl;
use RzSDK\Response\Response;
use RzSDK\Response\Info;
use RzSDK\Response\InfoType;
use RzSDK\Model\User\Registration\UserRegistrationRequestModel;
use RzSDK\User\Registration\UserRegistrationRegexValidation;
use RzSDK\User\Type\UserAuthType;
use function RzSDK\User\Type\getUserAuthTypeByValue;
use RzSDK\Database\SqliteConnection;
use RzSDK\Device\ClientDevice;
use RzSDK\Device\ClientIp;
use RzSDK\DatabaseSpace\DbUserTable;
use RzSDK\HTTPRequest\ValidationType;
use RzSDK\HTTPRequest\UserRegistrationRequest;
use RzSDK\Log\DebugLog;

?>
<?php
class UserRegistration {
    public function __construct() {
        /* DebugLog::log($_SERVER["HTTP_USER_AGENT"]);
        DebugLog::log($_SERVER); */
        $this->execute();
    }

    public function reExecute() {
        $userRegiRequest = new UserRegistrationRequest();
        $regiParamList = $userRegiRequest->getQuery();
        $userRegiRequestModel = new UserRegistrationRequestModel();
        $keyMapping = $userRegiRequestModel->propertyKeyMapping();
        $requestValue = array();
        foreach($regiParamList as $value) {
            if(array_key_exists($value, $_POST)) {
                $paramValue = $_POST[$value];
                $userRegiRequestModel->{$keyMapping[$value]} = $paramValue;
                $requestValue[$value] = $paramValue;
            } else {
                //Error array key not exist, return
            }
            $method = $value . "_rules";
            if(method_exists($userRegiRequest, $method)) {
                $validationRules = $userRegiRequest->{$method}();
                //DebugLog::log($validationRules);
                foreach($validationRules as $rules) {
                    if($rules == ValidationType::NOT_NULL) {
                        echo "type not null";
                    } else if($rules == ValidationType::EMAIL) {
                        echo "type email";
                    }
                }
            }
        }
        //DebugLog::log($userRegiRequestModel->deviceType);
    }

    public function execute() {
        if(!empty($_POST)) {
            $this->reExecute();
            //
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
        if($this->haveDbUser($userRegiRequestModel)) {
            $this->response(null, new Info("User already exists", InfoType::ERROR), $dataModel);
            return;
        }
        $this->userResitration($userRegiRequestModel);
    }

    private function regexValidation(UserRegistrationRequestModel $userRegiRequestModel) {
        $userRegistrationRegexValidation = new UserRegistrationRegexValidation();
        return $userRegistrationRegexValidation->execute($userRegiRequestModel);
    }

    private function haveDbUser(UserRegistrationRequestModel $userRegiRequestModel) {
        $url = dirname(ROOT_URL) . "/user/user.php";
        $dataModel = $userRegiRequestModel->toArrayKeyMapping($userRegiRequestModel);
        //
        $curl = new Curl($url);
        $result = $curl->exec(true, $dataModel) . "";
        $result = json_decode($result, true);
        //logPrint($result);
        $responseData = json_decode($result["body"], true);
        if(empty($responseData["body"])) {
            return false;
        }
        return true;
    }

    private function userResitration(UserRegistrationRequestModel $userRegiRequestModel) {
        $dbFullPath = "../" . DB_PATH . "/" . DB_FILE;
        $connection = new SqliteConnection($dbFullPath);
        $dataModel = $userRegiRequestModel->toArrayKeyMapping($userRegiRequestModel);
        $userId = time();
        $dateTime = date("Y-m-d H:i:s");
        $clientDevice = new ClientDevice();
        /* DebugLog::log($_SERVER["HTTP_USER_AGENT"]);
        DebugLog::log($_SERVER); */
        $sqlTable = DbUserTable::$userRegistration;
        $sqlQuery = "INSERT INTO {$sqlTable} VALUES("
        . "'" . $userId . "',"
        . " '" . $userRegiRequestModel->email . "',"
        . " " . true . ","
        . " " . true . ","
        //regi_date
        . " '" . $dateTime . "',"
        . " '" . $userRegiRequestModel->deviceType . "',"
        . " '" . $userRegiRequestModel->authType . "',"
        . " '" . $userRegiRequestModel->agentType . "',"
        . " '" . $clientDevice->getOs() . "',"
        . " '" . $clientDevice->getDevice() . "',"
        . " '" . $clientDevice->getBrowser() . "',"
        . " '" . ClientIp::ip() . "',"
        . " '" . $clientDevice->getHttpAgent() . "',"
        . " '" . $userId . "',"
        . " '" . $userId . "',"
        . " '" . $dateTime . "',"
        . " '" . $dateTime . "'"
        . ");";
        //echo $sqlQuery;
        $dbResult = $connection->query($sqlQuery);
        //logPrint($dbResult);
        $sqlTable = DbUserTable::$userInfo;
        $sqlQuery = "INSERT INTO {$sqlTable} VALUES("
        . "'" . $userId . "',"
        . "'" . $userRegiRequestModel->email . "',"
        . " " . true . ","
        . " '" . $userId . "',"
        . " '" . $userId . "',"
        . " '" . $dateTime . "',"
        . " '" . $dateTime . "'"
        . ");";
        //echo $sqlQuery;
        $dbResult = $connection->query($sqlQuery);
        //https://reintech.io/blog/php-password-hashing-securely-storing-verifying-passwords
        $password = password_hash($userRegiRequestModel->password, PASSWORD_DEFAULT);
        $sqlTable = DbUserTable::$userPassword;
        $sqlQuery = "INSERT INTO {$sqlTable} VALUES("
        . "'" . $userId . "',"
        . "'" . $password . "',"
        . " " . true . ","
        . " '" . $userId . "',"
        . " '" . $userId . "',"
        . " '" . $dateTime . "',"
        . " '" . $dateTime . "'"
        . ");";
        //echo $sqlQuery;
        $dbResult = $connection->query($sqlQuery);
        $this->response(null, new Info("Successful registration completed", InfoType::SUCCESS), $dataModel);
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
$userRegistration = new UserRegistration();
?>
