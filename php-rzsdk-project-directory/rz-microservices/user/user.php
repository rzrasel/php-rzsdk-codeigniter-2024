<?php
require_once("../include.php");
require_once("include.php");
?>
<?php
use RzSDK\Response\InfoType;
use RzSDK\Database\SqliteConnection;
use RzSDK\Model\User\UserRequestModel;
use RzSDK\Validation\BuildValidationRules;
use RzSDK\HTTPRequest\UserRequest;
use RzSDK\HTTPResponse\LaunchResponse;
use RzSDK\DatabaseSpace\DbUserTable;
use RzSDK\Log\DebugLog;
?>
<?php
class User {
    public function __construct() {
        $this->execute();
    }

    private function doDatabaseTask($userRegiRequestModel, $postedDataSet) {
        //
    }

    public function execute() {
        if(!empty($_POST)) {
            //DebugLog::log($_POST);
            $isValidated = $this->isValidated($_POST);
            //DebugLog::log($isValidated);
            if(!$isValidated["is_validate"]) {
                return;
            }
            //
            $userRequestModel = $isValidated["data_set"];
            //DebugLog::log($userRequestModel);
            $postedDataSet = $userRequestModel->toArrayKeyMapping($userRequestModel);
            //DebugLog::log($postedDataSet);
            $this->doDatabaseTask($userRequestModel, $postedDataSet);

            if($this->getDatabaseUser($userRequestModel, $postedDataSet)) {
                return;
            }
            //$this->response(null, "Successful registration completed", InfoType::SUCCESS, $postedDataSet);
        }
    }

    public function isValidated($dataSet) {
        //DebugLog::log($dataSet);
        $buildValidationRules = new BuildValidationRules();
        $userRequest = new UserRequest();
        $userParamList = $userRequest->getQuery();
        $userRequestModel = new UserRequestModel();
        $keyMapping = $userRequestModel->propertyKeyMapping();
        //$requestValue = array();
        //DebugLog::log($userParamList);
        $isValidated = true;
        foreach($userParamList as $value) {
            //Extract requested values from $_POST
            if(array_key_exists($value, $dataSet)) {
                $paramValue = $dataSet[$value];
                $userRequestModel->{$keyMapping[$value]} = $paramValue;
            } else {
                //Error array key not exist, return
                $this->response(null,
                    "Error! need to request by all parameter " . $value,
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
        //DebugLog::log($userRequestModel);
        return array(
            "is_validate"   => $isValidated,
            "data_set"          => $userRequestModel,
        );
    }

    private function getDatabaseUser($userRegiRequestModel, $postedDataSet) {
        $dbFullPath = "../" . DB_PATH . "/" . DB_FILE;
        //$dataModel = $userRegiRequestModel->toArrayKeyMapping($userRegiRequestModel);
        $connection = new SqliteConnection($dbFullPath);
        $sqlUserTable = DbUserTable::$userInfo;
        $sqlQuery = "SELECT * "
        . "FROM {$sqlUserTable} "
        . "WHERE"
        . " email = '{$userRegiRequestModel->email}'"
        . ";";
        //echo $sqlQuery;
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
                $this->response($dbData,
                    "Successful user found",
                    InfoType::SUCCESS,
                    $postedDataSet);
                return true;
            }
        }
        //
        $sqlUserRegiTable = DbUserTable::$userRegistration;
        $sqlQuery = "SELECT * "
        . "FROM {$sqlUserRegiTable} "
        . "WHERE"
        . " email = '{$userRegiRequestModel->email}'"
        . ";";
        //echo $sqlQuery;
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
                $this->response($dbData,
                    "Successful user found",
                    InfoType::SUCCESS,
                    $postedDataSet);
                return true;
            }
        }
        //$this->response($dbData, new Info("Error user not found", InfoType::ERROR), $dataModel);
        $this->response(null,
            "Error user not found",
            InfoType::ERROR,
            $postedDataSet);
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