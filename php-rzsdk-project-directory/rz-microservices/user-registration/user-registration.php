<?php
require_once("../include.php");
require_once("include.php");
?>
<?php
use RzSDK\Curl\Curl;
use RzSDK\Response\InfoType;
use RzSDK\User\Type\UserAuthType;
use function RzSDK\User\Type\getUserAuthTypeByValue;
use function RzSDK\Response\getInfoTypeByValue;
use RzSDK\Database\SqliteConnection;
use RzSDK\Device\ClientDevice;
use RzSDK\Device\ClientIp;
use RzSDK\DatabaseSpace\DbUserTable;
use RzSDK\Validation\BuildValidationRules;
use RzSDK\HTTPRequest\UserRegistrationRequest;
use RzSDK\HTTPResponse\LaunchResponse;
use RzSDK\Model\User\Registration\UserRegistrationDatabaseModel;
use RzSDK\Model\User\Registration\UserRegistrationRequestModel;
use RzSDK\Log\DebugLog;

?>
<?php
class UserRegistration {
    //
    //private UserRegistrationRequestModel $userRegiRequestModel;
    //
    public function __construct() {
        /* DebugLog::log($_SERVER["HTTP_USER_AGENT"]);
        DebugLog::log($_SERVER); */
        //$userRegiRequestModel = new UserRegistrationRequestModel();
        $this->execute();
    }

    private function doDatabaseTask($userRegiRequestModel, $postedDataSet) {
        //
        $databaseDataSet = $userRegiRequestModel->toDatabaseArrayKeyMapping($userRegiRequestModel);
        //DebugLog::log($databaseDataSet);
        //
        $userRegiDatabaseModel = new UserRegistrationDatabaseModel();
        //$userRegiDatabaseModel->test($userRegiRequestModel, $databaseDataSet);
        $userRegiDatabaseModel->test2($userRegiRequestModel, $databaseDataSet);
    }

    public function execute() {
        if(!empty($_POST)) {
            $isValidated = $this->isValidated($_POST);
            if(!$isValidated["is_validate"]) {
                return;
            }
            //
            //$userRegiRequestModel = new UserRegistrationRequestModel();
            $userRegiRequestModel = $isValidated["data_set"];
            //DebugLog::log($userRegiRequestModel);
            $postedDataSet = $userRegiRequestModel->toArrayKeyMapping($userRegiRequestModel);
            //DebugLog::log($postedDataSet);
            $this->doDatabaseTask($userRegiRequestModel, $postedDataSet);

            $enumValue = $userRegiRequestModel->authType;
            $userAuthType = getUserAuthTypeByValue($enumValue);

            if(!empty($userAuthType)) {
                if($userAuthType == UserAuthType::EMAIL) {
                    $this->registeredByEmail($userRegiRequestModel, $postedDataSet);
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
            //$this->response(null, "Successful registration completed", InfoType::SUCCESS, $dataModel);
        }
    }

    public function isValidated($requestDataSet) {
        //DebugLog::log($requestDataSet);
        $buildValidationRules = new BuildValidationRules();
        $userRegiRequest = new UserRegistrationRequest();
        $regiParamList = $userRegiRequest->getQuery();
        $userRegiRequestModel = new UserRegistrationRequestModel();
        $keyMapping = $userRegiRequestModel->propertyKeyMapping();
        //$requestValue = array();
        //DebugLog::log($regiParamList);
        $isValidated = true;
        //$returnValue = null;
        foreach($regiParamList as $value) {
            //Extract requested values from $_POST
            if(array_key_exists($value, $requestDataSet)) {
                $paramValue = $requestDataSet[$value];
                $userRegiRequestModel->{$keyMapping[$value]} = $paramValue;
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
            if(method_exists($userRegiRequest, $method)) {
                $validationRules = $userRegiRequest->{$method}();
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
            "data_set"          => $userRegiRequestModel,
        );
    }

    private function registeredByEmail(UserRegistrationRequestModel $userRegiRequestModel, $postedDataSet) {
        //$dataModel = $userRegiRequestModel->toArrayKeyMapping($userRegiRequestModel);
        /*if(!$this->regexValidation($userRegiRequestModel)) {
            return;
        }*/
        if($this->haveDatabaseUser($postedDataSet)) {
            $this->response(null,
                "User already exists",
                InfoType::ERROR,
                $postedDataSet);
            return;
        }
        $this->doUserResitration($userRegiRequestModel, $postedDataSet);
    }

    /*private function regexValidation(UserRegistrationRequestModel $userRegiRequestModel) {
        $userRegistrationRegexValidation = new UserRegistrationRegexValidation();
        return $userRegistrationRegexValidation->execute($userRegiRequestModel);
    }*/

    private function haveDatabaseUser($postedDataSet) {
        $url = dirname(ROOT_URL) . "/user/user.php";
        //$dataModel = $userRegiRequestModel->toArrayKeyMapping($userRegiRequestModel);
        //DebugLog::log($postedDataSet);
        $curl = new Curl($url);
        $result = $curl->exec(true, $postedDataSet) . "";
        $result = json_decode($result, true);
        //DebugLog::log($result);
        $responseData = json_decode($result["body"], true);
        //DebugLog::log($responseData);
        if(empty($responseData["body"])) {
            //echo "False Data";
            return false;
        }
        $responseInfoType = $responseData["info"]["type"];
        //DebugLog::log($responseInfoType);
        $responseType = getInfoTypeByValue($responseInfoType);
        //DebugLog::log($responseType);
        if($responseType == InfoType::ERROR) {
            return false;
        }
        //echo "True Data";
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
