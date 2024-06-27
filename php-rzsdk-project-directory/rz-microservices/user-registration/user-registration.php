<?php
require_once("../include.php");
require_once("include.php");
?>
<?php
use RzSDK\Curl\Curl;
use RzSDK\Response\InfoType;
use RzSDK\Model\User\Registration\UserRegistrationRequestModel;
use RzSDK\User\Type\UserAuthType;
use function RzSDK\User\Type\getUserAuthTypeByValue;
use RzSDK\Database\SqliteConnection;
use RzSDK\Device\ClientDevice;
use RzSDK\Device\ClientIp;
use RzSDK\DatabaseSpace\DbUserTable;
use RzSDK\Validation\BuildValidationRules;
use RzSDK\HTTPRequest\UserRegistrationRequest;
use RzSDK\HTTPResponse\LaunchResponse;
use RzSDK\Log\DebugLog;

?>
<?php
class UserRegistration {
    public function __construct() {
        /* DebugLog::log($_SERVER["HTTP_USER_AGENT"]);
        DebugLog::log($_SERVER); */
        $this->execute();
    }

    public function execute() {
        if(!empty($_POST)) {
            $isValidated = $this->isValidated($_POST);
            if(!$isValidated["is_validate"]) {
                return;
            }
            //
            $userRegiRequestModel = $isValidated["data_set"];
            $dataModel = $userRegiRequestModel->toArrayKeyMapping($userRegiRequestModel);

            $enumValue = $userRegiRequestModel->authType;
            $userAuthType = getUserAuthTypeByValue($enumValue);

            if(!empty($userAuthType)) {
                if($userAuthType == UserAuthType::EMAIL) {
                    $this->registeredByEmail($userRegiRequestModel, $dataModel);
                } else {
                    $this->response(null,
                        "Error! request parameter not matched out of type",
                        InfoType::ERROR,
                        $dataModel);
                }
            } else {
                $this->response(null,
                    "Error! request parameter not matched",
                    InfoType::ERROR,
                    $dataModel);
            }
            //$this->response(null, "Successful registration completed", InfoType::SUCCESS, $dataModel);
        }
    }

    public function isValidated($dataSet) {
        $buildValidationRules = new BuildValidationRules();
        $userRegiRequest = new UserRegistrationRequest();
        $regiParamList = $userRegiRequest->getQuery();
        $userRegiRequestModel = new UserRegistrationRequestModel();
        $keyMapping = $userRegiRequestModel->propertyKeyMapping();
        //$requestValue = array();
        //DebugLog::log($regiParamList);
        $isValidated = true;
        $returnValue = null;
        foreach($regiParamList as $value) {
            //Extract requested values from $_POST
            if(array_key_exists($value, $dataSet)) {
                $paramValue = $dataSet[$value];
                $userRegiRequestModel->{$keyMapping[$value]} = $paramValue;
            } else {
                //Error array key not exist, return
                $this->response(null,
                    "Error! need to request by all parameter",
                    InfoType::ERROR,
                    $dataSet);
                $isValidated = false;
            }
            //Execute and check validation rules
            $method = $value . "_rules";
            if(method_exists($userRegiRequest, $method)) {
                $validationRules = $userRegiRequest->{$method}();
                //DebugLog::log($validationRules);
                $isValidated = $buildValidationRules->setRules($paramValue, $validationRules)
                    ->run();
                if(!$isValidated["is_validate"]) {
                    $this->response(null,
                        $isValidated["message"],
                        InfoType::ERROR,
                        $dataSet);
                    $isValidated = false;
                }
            }
        }
        $returnValue = $userRegiRequestModel;
        //DebugLog::log($returnValue);
        return array(
            "is_validate"   => $isValidated,
            "data_set"          => $returnValue,
        );
    }

    private function registeredByEmail(UserRegistrationRequestModel $userRegiRequestModel, $paramData) {
        //$dataModel = $userRegiRequestModel->toArrayKeyMapping($userRegiRequestModel);
        /*if(!$this->regexValidation($userRegiRequestModel)) {
            return;
        }*/
        if($this->haveDatabaseUser($paramData)) {
            $this->response(null,
                "User already exists",
                InfoType::ERROR,
                $paramData);
            return;
        }
        //$this->doUserResitration($userRegiRequestModel, $paramData);
    }

    /*private function regexValidation(UserRegistrationRequestModel $userRegiRequestModel) {
        $userRegistrationRegexValidation = new UserRegistrationRegexValidation();
        return $userRegistrationRegexValidation->execute($userRegiRequestModel);
    }*/

    private function haveDatabaseUser($paramData) {
        $url = dirname(ROOT_URL) . "/user/user.php";
        //$dataModel = $userRegiRequestModel->toArrayKeyMapping($userRegiRequestModel);
        //
        $curl = new Curl($url);
        $result = $curl->exec(true, $paramData) . "";
        $result = json_decode($result, true);
        DebugLog::log($result);
        $responseData = json_decode($result["body"], true);
        if(empty($responseData["body"])) {
            return false;
        }
        return true;
    }

    private function doUserResitration(UserRegistrationRequestModel $userRegiRequestModel, $paramData) {
        $dbFullPath = "../" . DB_PATH . "/" . DB_FILE;
        $connection = new SqliteConnection($dbFullPath);
        //$dataModel = $userRegiRequestModel->toArrayKeyMapping($userRegiRequestModel);
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
        $this->response(null,
            "Successful registration completed",
            InfoType::SUCCESS,
            $paramData);
    }

    private function response($body, $message, InfoType $infoType, $parameter = null) {
        $launchResponse = new LaunchResponse();
        $launchResponse->setBody($body)
            ->setInfo($message, $infoType)
            ->setParameter($parameter)
            ->execute();
    }
}
?>
<?php
$userRegistration = new UserRegistration();
?>
