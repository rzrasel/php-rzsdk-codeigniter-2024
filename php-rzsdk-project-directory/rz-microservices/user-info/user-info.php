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
use RzSDK\Service\Listener\ServiceListener;
use RzSDK\Service\Adapter\User\Info\UserInfoRequestValidationService;
use RzSDK\Service\Adapter\User\Info\UserInfoUserInfoDatabaseService;
use RzSDK\Utils\ObjectPropertyWizard;
use RzSDK\DatabaseSpace\UserInfoTable;
use RzSDK\Model\User\UserInfo\UserInfoResponseModel;
use RzSDK\Service\Adapter\User\Info\UserInfoUserRegistrationDatabaseService;
use RzSDK\DatabaseSpace\UserRegistrationTable;
use RzSDK\Log\DebugLog;
?>
<?php
class UserInfo {
    //
    private UserInfoRequest $userInfoRequest;
    public $postedDataSet;
    //
    public function __construct() {
        $this->execute();
    }

    /*private function doDatabaseTask($userInfoRequestModel, $postedDataSet) {
        DebugLog::log($sqlQuery);
    }*/

    public function execute() {
        if(!empty($_POST)) {
            $this->postedDataSet = $_POST;
            $userInfoRequestValidationAction = new class($this) implements ServiceListener {
                private UserInfo $outerInstance;

                public function __construct($outerInstance) {
                    $this->outerInstance = $outerInstance;
                }

                public function onError($dataSet, $message) {
                    $this->outerInstance->response(null, $message, InfoType::ERROR, $dataSet);
                }

                public function onSuccess($dataSet, $message) {
                    $this->outerInstance->checkUserInfoUserExists($dataSet);
                }
            };
            //
            (new UserInfoRequestValidationService(
                $userInfoRequestValidationAction
            ))->execute($this->postedDataSet);
        }
    }

    public function checkUserInfoUserExists(UserInfoRequest $userInfoRequest) {
        //DebugLog::log($userInfoRequest);
        $this->userInfoRequest = $userInfoRequest;
        //
        (new UserInfoUserInfoDatabaseService(
            new class($this) implements ServiceListener {
                private UserInfo $outerInstance;

                public function __construct($outerInstance) {
                    $this->outerInstance = $outerInstance;
                }

                public function onError($dataSet, $message) {
                    $this->outerInstance->response(null, $message, InfoType::ERROR, $dataSet);
                }

                public function onSuccess($dataSet, $message) {
                    if(empty($dataSet)) {
                        $this->outerInstance->checkUserRegistrationUserExists();
                        return;
                    }
                    //DebugLog::log($dataSet);
                    $this->outerInstance->userExistsInUserInfoDatabase($dataSet, $message);
                }
            }
        ))->execute($this->userInfoRequest, $this->postedDataSet);
    }

    public function checkUserRegistrationUserExists() {
        //DebugLog::log($this->postedDataSet);
        (new UserInfoUserRegistrationDatabaseService(
            new class($this) implements ServiceListener {
                private UserInfo $outerInstance;

                public function __construct($outerInstance) {
                    $this->outerInstance = $outerInstance;
                }

                public function onError($dataSet, $message) {
                    $this->outerInstance->response(null, $message, InfoType::ERROR, $dataSet);
                }

                public function onSuccess($dataSet, $message) {
                    if(empty($dataSet)) {
                        $this->outerInstance->response(null, $message, InfoType::DB_DATA_NOT_FOUND, $this->outerInstance->postedDataSet);
                        return;
                    }
                    $this->outerInstance->userExistsInUserRegistrationDatabase($dataSet, $message);
                }
            }
        ))->execute($this->userInfoRequest, $this->postedDataSet);
    }

    public function userExistsInUserInfoDatabase(UserInfoTable $userInfoTable, $message) {
        $userInfoResponseModel = new UserInfoResponseModel();
        $userInfoResponseModel->user_id = $userInfoTable->user_id;
        $userInfoResponseModel->user_email = $userInfoTable->email;
        $retVal = $userInfoResponseModel->toParameterKeyValue();
        //DebugLog::log($retVal);
        $message = $message . "." . __LINE__;
        $this->response($retVal, $message, InfoType::SUCCESS, $this->postedDataSet);
    }

    public function userExistsInUserRegistrationDatabase(UserRegistrationTable $userRegiTable, $message) {
        $userInfoResponseModel = new UserInfoResponseModel();
        $userInfoResponseModel->user_id = $userRegiTable->user_regi_id;
        $userInfoResponseModel->user_email = $userRegiTable->email;
        $retVal = $userInfoResponseModel->toParameterKeyValue();
        //DebugLog::log($retVal);
        $message = $message . "." . __LINE__;
        $this->response($retVal, $message, InfoType::SUCCESS, $this->postedDataSet);
    }

    public function response($body, $message, InfoType $infoType, $parameter = null) {
        $launchResponse = new LaunchResponse();
        $launchResponse->setBody($body)
            ->setInfo($message, $infoType)
            ->setParameter($parameter)
            ->execute();
    }

    /*public function executeOld() {
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

            /-*if($this->getDatabaseUser($userInfoRequestModel, $postedDataSet)) {
                return;
            }*-/
            $this->bindUserInfoSqlQuery($userInfoRequestModel, $postedDataSet);
            //$this->response(null, "Successful registration completed", InfoType::SUCCESS, $postedDataSet);
        }
    }*/

    /*public function isValidated($dataSet) {
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
                    "Error! need to request by all parameter error code " . __LINE__,
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
    }*/

    /*private function bindUserInfoSqlQuery($userInfoRequestModel, $postedDataSet) {
        //DebugLog::log($userInfoRequestModel);
        //
        $dbConn = $this->getDbConnection();
        //
        //$userInfoRequestModel->email = $userInfoRequestModel->email . "(ajsfdjf)";
        $sqlQuery = $this->getSelectUserInfoSql($userInfoRequestModel);
        $dbResult = $this->doRunSelectQuery($dbConn, $sqlQuery);
        $userInfoDatabaseModel = new UserInfoDatabaseModel();
        $userInfoTable = $userInfoDatabaseModel->fillDbColumn($dbResult);
        //DebugLog::log($userInfoTable->getColumnWithKey());
        if(!empty($userInfoTable)) {
            $this->response($userInfoTable->getColumnWithKey(),
                "Successful user found code " . __LINE__,
                InfoType::SUCCESS,
                $postedDataSet);
        } else {
            //$userInfoRequestModel->email = str_replace("(ajsfdjf)", "", $userInfoRequestModel->email);
            $sqlQuery = $this->getSelectUserRegiSql($userInfoRequestModel);
            $dbResult = $this->doRunSelectQuery($dbConn, $sqlQuery);
            $userRegistrationDatabaseModel = new UserRegistrationDatabaseModel();
            $userRegiTable = $userRegistrationDatabaseModel->fillDbColumn($dbResult);
            //DebugLog::log($userRegiTable->getColumnWithKey());
            if(!empty($userRegiTable)) {
                $this->response($userRegiTable->getColumnWithKey(),
                    "Successful user found code " . __LINE__,
                    InfoType::SUCCESS,
                    $postedDataSet);
            } else {
                $this->response(null,
                    "Error! user not found error code " . __LINE__,
                    InfoType::DB_DATA_NOT_FOUND,
                    $postedDataSet);
            }
        }
    }*/

    /*private function getSelectUserInfoSql($userInfoRequestModel) {
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
    }*/

    /*private function getSelectUserRegiSql($userInfoRequestModel) {
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
    }*/

    /*private function doRunSelectQuery($dbConn, $sqlQuery) {
        return $dbConn->query($sqlQuery);
    }*/

    /*private function getDbConnection() {
        $dbFullPath = "../" . DB_PATH . "/" . DB_FILE;
        return new SqliteConnection($dbFullPath);
    }*/
}
?>
<?php
new UserInfo();
?>