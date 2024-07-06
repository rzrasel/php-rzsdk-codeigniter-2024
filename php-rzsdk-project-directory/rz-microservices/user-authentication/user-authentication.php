<?php
require_once("../include.php");
require_once("include.php");
?>
<?php
use RzSDK\Response\Response;
use RzSDK\Response\Info;
use RzSDK\Response\InfoType;
use RzSDK\Database\SqliteConnection;
use RzSDK\Model\User\Authentication\UserAuthenticationRequestModel;
use RzSDK\User\Authentication\UserRegistrationRegexValidation;
use RzSDK\HTTPRequest\UserAuthenticationRequest;
use RzSDK\Validation\BuildValidationRules;
use RzSDK\DatabaseSpace\DbUserTable;
use RzSDK\User\Type\UserAuthType;
use function RzSDK\User\Type\getUserAuthTypeByValue;
use RzSDK\Log\DebugLog;

?>
<?php
class UserAuthentication {
    public function __construct() {
        $this->execute();
    }

    private function doDatabaseTask($userAuthenticationRequestModel, $postedDataSet) {
        DebugLog::log($userAuthenticationRequestModel);
        DebugLog::log($userAuthenticationRequestModel->arrayKeyMap());
    }

    public function execute() {
        if(!empty($_POST)) {
            //DebugLog::log($_POST);
            $isValidated = $this->isValidated($_POST);
            if(!$isValidated["is_validate"]) {
                return;
            }
            $userAuthenticationRequestModel = $isValidated["data_set"];
            //DebugLog::log($userRegiRequestModel);
            $postedDataSet = $userAuthenticationRequestModel->toArrayKeyMapping($userAuthenticationRequestModel);
            //DebugLog::log($postedDataSet);
            $this->doDatabaseTask($userAuthenticationRequestModel, $postedDataSet);

            $enumValue = $userAuthenticationRequestModel->authType;
            $userAuthType = getUserAuthTypeByValue($enumValue);

            if(!empty($userAuthType)) {
                if($userAuthType == UserAuthType::EMAIL) {
                    $this->userAuthenticationByEmail($userAuthenticationRequestModel, $postedDataSet);
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
        $userAuthenticationRequest = new UserAuthenticationRequest();
        $authParamList = $userAuthenticationRequest->getQuery();
        //DebugLog::log($authParamList);
        $userAuthenticationRequestModel = new UserAuthenticationRequestModel();
        $keyMapping = $userAuthenticationRequestModel->propertyKeyMapping();
        //DebugLog::log($keyMapping);
        $isValidated = true;
        foreach($authParamList as $value) {
            //Extract requested values from $_POST
            if(array_key_exists($value, $requestDataSet)) {
                $paramValue = $requestDataSet[$value];
                $userAuthenticationRequestModel->{$keyMapping[$value]} = $paramValue;
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
            if(method_exists($userAuthenticationRequest, $method)) {
                $validationRules = $userAuthenticationRequest->{$method}();
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
            "data_set"          => $userAuthenticationRequestModel,
        );
    }

    private function userAuthenticationByEmail($userAuthenticationRequestModel, $postedDataSet) {
        $this->getDbUserAuthentication($userAuthenticationRequestModel, $postedDataSet);
    }

    private function getDbUserAuthentication($userAuthenticationRequestModel, $postedDataSet) {
        return $this->getDbUser($userAuthenticationRequestModel);
    }

    private function doRunSelectSql($dbConn, $sqlQuery) {
        return $dbConn->query($sqlQuery);
    }

    private function getSqlConnection() {
        $dbFullPath = "../" . DB_PATH . "/" . DB_FILE;
        return new SqliteConnection($dbFullPath);
    }

    private function response($body, Info $info, $parameter = null) {
        $response = new Response();
        $response->body         = $body;
        $response->info         = $info;
        $response->parameter    = $parameter;
        echo $response->toJson();
    }

    public function executeOld() {
        if(!empty($_POST)) {
            $userRegiRequestModel = new UserRegistrationRequestModel();
            $userRegiRequestModel->agentType = $_POST[$userRegiRequestModel->agentType];
            $userRegiRequestModel->authType = $_POST[$userRegiRequestModel->authType];
            $userRegiRequestModel->deviceType = $_POST[$userRegiRequestModel->deviceType];
            $userRegiRequestModel->email = $_POST[$userRegiRequestModel->email];
            $userRegiRequestModel->password = $_POST[$userRegiRequestModel->password];
            $dataModel = $userRegiRequestModel->toArrayKeyMapping($userRegiRequestModel);

            if(!$this->regexValidation($userRegiRequestModel)) {
                return;
            }
            //DebugLog::log($this->getDbUser($userRegiRequestModel));
            if($this->getDbUser($userRegiRequestModel)) {
                return;
            }
            //$this->response(null, new Info("Successful registration completed", InfoType::SUCCESS), $dataModel);
        }
    }

    private function regexValidation(UserRegistrationRequestModel $userRegiRequestModel) {
        $userRegistrationRegexValidation = new UserRegistrationRegexValidation();
        return $userRegistrationRegexValidation->execute($userRegiRequestModel);
    }

    private function getDbUser($userAuthenticationRequestModel) {
        $dbFullPath = "../" . DB_PATH . "/" . DB_FILE;
        $dataModel = $userAuthenticationRequestModel->toArrayKeyMapping($userAuthenticationRequestModel);
        $connection = new SqliteConnection($dbFullPath);
        $tableUser = DbUserTable::$userInfo;
        $tableUserPass = DbUserTable::$userPassword;
        $sqlQuery = "SELECT * FROM {$tableUser} AS user "
        . "INNER JOIN {$tableUserPass} AS password "
        . "ON"
        . " user.user_id = password.user_id "
        . "WHERE"
        . " user.email = '{$userAuthenticationRequestModel->email}'"
        . " AND user.status = '1'"
        . " AND password.status = '1'"
        . ";";
        //echo $sqlQuery;
        $dbData = array();
        $dbResult = $connection->query($sqlQuery);
        if($dbResult != null) {
            foreach($dbResult as $row) {
                $dbData["user_id"]  = $row["user_id"];
                $dbData["email"]    = $row["email"];
                $dbData["password"] = $row["password"];
            }
            //DebugLog::log("debug_log_print");
            if(!empty($dbData)) {
                //DebugLog::log("debug_log_print");
                $this->response($dbData, new Info("Successful user found", InfoType::SUCCESS), $dataModel);
                return true;
            } else {
                //DebugLog::log("debug_log_print");
                $this->response($dbData, new Info("Error user not found", InfoType::INFO), $dataModel);
                return false;
            }
        }
        //DebugLog::log("debug_log_print");
        $this->response($dbData, new Info("Error user not found", InfoType::ERROR), $dataModel);
        return false;
    }
}
?>
<?php
$userAuthentication = new UserAuthentication();
?>
