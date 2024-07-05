<?php
require_once("../include.php");
require_once("include.php");
?>
<?php
use RzSDK\Response\InfoType;
use RzSDK\Database\SqliteConnection;
use RzSDK\Model\User\UserInfo\UserInfoDatabaseModel;
use RzSDK\Model\User\UserInfo\UserRegistrationDatabaseModel;
use RzSDK\Model\User\UserInfo\UserInfoRequestModel;
use RzSDK\Validation\BuildValidationRules;
use RzSDK\HTTPRequest\UserInfoRequest;
use RzSDK\HTTPResponse\LaunchResponse;
use RzSDK\DatabaseSpace\DbUserTable;
use RzSDK\SqlQueryBuilder\SqlQueryBuilder;
use RzSDK\Log\DebugLog;
?>
<?php
class UserInfo {
    public function __construct() {
        $this->execute();
    }

    /*private function doDatabaseTask($userInfoRequestModel, $postedDataSet) {
        //DebugLog::log($userInfoRequestModel);
        //DebugLog::log($userInfoRequestModel->arrayKeyMap());
        $userInfoTable = DbUserTable::$userInfo;
        $userInfoDatabaseModel = new UserInfoDatabaseModel();
        $userEmail = $userInfoDatabaseModel->getColumnName("email");
        $sqlQueryBuilder = new SqlQueryBuilder();
        $sqlQuery = $sqlQueryBuilder->select()
            ->from($userInfoTable, "")
            ->where("", array($userEmail => $userInfoRequestModel->email))
            ->build();
        DebugLog::log($sqlQuery);
        //
        $userInfoTable = DbUserTable::$userRegistration;
        $userRegistrationDatabaseModel = new UserRegistrationDatabaseModel();
        $userEmail = $userRegistrationDatabaseModel->getColumnName("email");
        $sqlQueryBuilder = new SqlQueryBuilder();
        $sqlQuery = $sqlQueryBuilder->select()
            ->from($userInfoTable, "")
            ->where("", array($userEmail => $userInfoRequestModel->email))
            ->build();
        DebugLog::log($sqlQuery);
    }*/

    public function execute() {
        if(!empty($_POST)) {
            //DebugLog::log($_POST);
            $isValidated = $this->isValidated($_POST);
            //DebugLog::log($isValidated);
            if(!$isValidated["is_validate"]) {
                return;
            }
            //
            $userInfoRequestModel = $isValidated["data_set"];
            //DebugLog::log($userInfoRequestModel);
            $postedDataSet = $userInfoRequestModel->toArrayKeyMapping($userInfoRequestModel);
            //DebugLog::log($postedDataSet);
            //$this->doDatabaseTask($userInfoRequestModel, $postedDataSet);

            /*if($this->getDatabaseUser($userInfoRequestModel, $postedDataSet)) {
                return;
            }*/
            $this->bindUserInfoSqlQuery($userInfoRequestModel, $postedDataSet);
            //$this->response(null, "Successful registration completed", InfoType::SUCCESS, $postedDataSet);
        }
    }

    public function isValidated($dataSet) {
        //DebugLog::log($dataSet);
        $buildValidationRules = new BuildValidationRules();
        $userInfoRequest = new UserInfoRequest();
        $userParamList = $userInfoRequest->getQuery();
        $userInfoRequestModel = new UserInfoRequestModel();
        $keyMapping = $userInfoRequestModel->propertyKeyMapping();
        //$requestValue = array();
        //DebugLog::log($userParamList);
        $isValidated = true;
        foreach($userParamList as $value) {
            //Extract requested values from $_POST
            if(array_key_exists($value, $dataSet)) {
                $paramValue = $dataSet[$value];
                $userInfoRequestModel->{$keyMapping[$value]} = $paramValue;
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
            if(method_exists($userInfoRequestModel, $method)) {
                $validationRules = $userInfoRequestModel->{$method}();
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
            "data_set"          => $userInfoRequestModel,
        );
    }

    private function bindUserInfoSqlQuery($userInfoRequestModel, $postedDataSet) {
        //DebugLog::log($userInfoRequestModel);
        //
        $dbConn = $this->getSqlConnection();
        //
        //$userInfoRequestModel->email = $userInfoRequestModel->email . "(ajsfdjf)";
        $sqlQuery = $this->getSelectUserInfoSql($userInfoRequestModel);
        $dbResult = $this->doRunSelectSql($dbConn, $sqlQuery);
        $userInfoDatabaseModel = new UserInfoDatabaseModel();
        $userInfoTable = $userInfoDatabaseModel->fillDbColumn($dbResult);
        //DebugLog::log($userInfoTable->getColumnWithKey());
        if(!empty($userInfoTable)) {
            $this->response($userInfoTable->getColumnWithKey(),
                "Successful user found",
                InfoType::SUCCESS,
                $postedDataSet);
        } else {
            //$userInfoRequestModel->email = str_replace("(ajsfdjf)", "", $userInfoRequestModel->email);
            $sqlQuery = $this->getSelectUserRegiSql($userInfoRequestModel);
            $dbResult = $this->doRunSelectSql($dbConn, $sqlQuery);
            $userRegistrationDatabaseModel = new UserRegistrationDatabaseModel();
            $userRegiTable = $userRegistrationDatabaseModel->fillDbColumn($dbResult);
            //DebugLog::log($userRegiTable->getColumnWithKey());
            if(!empty($userRegiTable)) {
                $this->response($userRegiTable->getColumnWithKey(),
                    "Successful user found",
                    InfoType::SUCCESS,
                    $postedDataSet);
            } else {
                $this->response(null,
                    "Error user not found",
                    InfoType::ERROR,
                    $postedDataSet);
            }
        }
    }

    private function getSelectUserInfoSql($userInfoRequestModel) {
        $userInfoTable = DbUserTable::$userInfo;
        $userInfoDatabaseModel = new UserInfoDatabaseModel();
        $userEmail = $userInfoDatabaseModel->getColumnName("email");
        $sqlQueryBuilder = new SqlQueryBuilder();
        $sqlQuery = $sqlQueryBuilder->select()
            ->from($userInfoTable, "")
            ->where("", array($userEmail => $userInfoRequestModel->email))
            ->build();
        //DebugLog::log($sqlQuery);
        return $sqlQuery;
    }

    private function getSelectUserRegiSql($userInfoRequestModel) {
        $userInfoTable = DbUserTable::$userRegistration;
        $userRegistrationDatabaseModel = new UserRegistrationDatabaseModel();
        $userEmail = $userRegistrationDatabaseModel->getColumnName("email");
        $sqlQueryBuilder = new SqlQueryBuilder();
        $sqlQuery = $sqlQueryBuilder->select()
            ->from($userInfoTable, "")
            ->where("", array($userEmail => $userInfoRequestModel->email))
            ->build();
        //DebugLog::log($sqlQuery);
        return $sqlQuery;
    }

    private function doRunSelectSql($dbConn, $sqlQuery) {
        return $dbConn->query($sqlQuery);
    }

    private function getSqlConnection() {
        $dbFullPath = "../" . DB_PATH . "/" . DB_FILE;
        return new SqliteConnection($dbFullPath);
    }

    private function response($body, $message, InfoType $infoType, $parameter = null) {
        $launchResponse = new LaunchResponse();
        $launchResponse->setBody($body)
            ->setInfo($message, $infoType)
            ->setParameter($parameter)
            ->execute();
    }

    /*private function getDatabaseUser($userRegiRequestModel, $postedDataSet) {
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
    }*/
}
?>
<?php
new UserInfo();
?>