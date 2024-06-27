<?php
require_once("../include.php");
require_once("include.php");
?>
<?php
use RzSDK\Response\Response;
use RzSDK\Response\Info;
use RzSDK\Response\InfoType;
use RzSDK\Database\SqliteConnection;
use RzSDK\Model\User\UserRequestModel;
use RzSDK\User\UserRegistrationRegexValidation;
use RzSDK\Validation\BuildValidationRules;
use RzSDK\HTTPRequest\UserRequest;
use RzSDK\HTTPResponse\LaunchResponse;
use RzSDK\Log\DebugLog;
?>
<?php
class User {
    public function __construct() {
        $this->execute();
    }

    public function execute() {
        if(!empty($_POST)) {
            $isValidated = $this->isValidated($_POST);
            DebugLog::log($isValidated);
            if(!$isValidated["is_validate"]) {
                return;
            }
            //
            $userRequestModel = $isValidated["data_set"];
            DebugLog::log($userRequestModel);
        }
    }

    public function isValidated($dataSet) {
        DebugLog::log($dataSet);
        $buildValidationRules = new BuildValidationRules();
        $userRequest = new UserRequest();
        $userParamList = $userRequest->getQuery();
        $userRequestModel = new UserRequestModel();
        $keyMapping = $userRequestModel->propertyKeyMapping();
        //$requestValue = array();
        //DebugLog::log($userParamList);
        $isValidated = true;
        $returnValue = null;
        foreach($userParamList as $value) {
            //Extract requested values from $_POST
            if(array_key_exists($value, $dataSet)) {
                $paramValue = $dataSet[$value];
                $userRequestModel->{$keyMapping[$value]} = $paramValue;
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
            if(method_exists($userRequestModel, $method)) {
                $validationRules = $userRequestModel->{$method}();
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
        $returnValue = $userRequestModel;
        DebugLog::log($returnValue);
        return array(
            "is_validate"   => $isValidated,
            "data_set"          => $returnValue,
        );
    }

    public function executeOld01() {
        if(!empty($_POST)) {
            $userRequestModel = new UserRequestModel();
            $userRequestModel->agentType = $_POST[$userRequestModel->agentType];
            $userRequestModel->authType = $_POST[$userRequestModel->authType];
            $userRequestModel->deviceType = $_POST[$userRequestModel->deviceType];
            $userRequestModel->email = $_POST[$userRequestModel->email];
            $userRequestModel->password = $_POST[$userRequestModel->password];
            $dataModel = $userRequestModel->toArrayKeyMapping($userRequestModel);

            if(!$this->regexValidation($userRequestModel)) {
                return;
            }
            if($this->getDbUser($userRequestModel)) {
                return;
            }
            //$this->response(null, new Info("Successful registration completed", InfoType::SUCCESS), $dataModel);
        }
    }

    private function regexValidation(UserRequestModel $userRequestModel) {
        $userRegistrationRegexValidation = new UserRegistrationRegexValidation();
        return $userRegistrationRegexValidation->execute($userRequestModel);
    }

    private function getDbUser(UserRegistrationRequestModel $userRegiRequestModel) {
        $dbFullPath = "../" . DB_PATH . "/" . DB_FILE;
        $dataModel = $userRegiRequestModel->toArrayKeyMapping($userRegiRequestModel);
        $connection = new SqliteConnection($dbFullPath);
        $sqlQuery = "SELECT * "
        . "FROM user "
        . "WHERE"
        . " email = '{$userRegiRequestModel->email}'"
        . ";";
        $dbData = array();
        $dbResult = $connection->query($sqlQuery);
        if($dbResult != null) {
            foreach($dbResult as $row) {
                $dbData["user_id"]          = $row["user_id"];
                $dbData["email"]            = $row["email"];
                $dbData["status"]           = $row["status"];
                $dbData["modified_by"]      = $row["modified_by"];
                $dbData["created_by"]       = $row["created_by"];
                $dbData["modified_date"]    = $row["modified_date"];
                $dbData["created_date"]     = $row["created_date"];
            }
            //echo $sqlQuery;
            if(!empty($dbData)) {
                //echo "user table is empty";
                //$this->response($dbData, new Info("Successful user found", InfoType::SUCCESS), $dataModel);
                $this->response(null,
                    "Successful user found",
                    InfoType::SUCCESS,
                    $dataModel);
                return true;
            }
        }
        //
        $sqlQuery = "SELECT * "
        . "FROM user_registration "
        . "WHERE"
        . " email = '{$userRegiRequestModel->email}'"
        . ";";
        $dbResult = $connection->query($sqlQuery);
        if($dbResult != null) {
            foreach($dbResult as $row) {
                $dbData["user_regi_id"]     = $row["user_regi_id"];
                $dbData["email"]            = $row["email"];
                $dbData["status"]           = $row["status"];
                $dbData["modified_by"]      = $row["modified_by"];
                $dbData["created_by"]       = $row["created_by"];
                $dbData["modified_date"]    = $row["modified_date"];
                $dbData["created_date"]     = $row["created_date"];
            }
            //echo $sqlQuery;
            if(!empty($dbData)) {
                //echo "user_registration table is empty";
                //$this->response($dbData, new Info("Successful user found", InfoType::SUCCESS), $dataModel);
                $this->response(null,
                    "Successful user found",
                    InfoType::SUCCESS,
                    $dataModel);
                return true;
            }
        }
        //$this->response($dbData, new Info("Error user not found", InfoType::ERROR), $dataModel);
        $this->response(null,
            "Error user not found",
            InfoType::ERROR,
            $dataModel);
        return false;
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
new User();
?>