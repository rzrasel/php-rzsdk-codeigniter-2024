<?php
require_once("../include.php");
require_once("include.php");
?>
<?php
use RzSDK\Curl\Curl;
use RzSDK\Response\InfoType;
use RzSDK\User\Type\UserAuthType;
use RzSDK\User\Type\UserAuthTypeExtension;
use function RzSDK\Response\getInfoTypeByValue;
use RzSDK\Database\SqliteConnection;
use RzSDK\Device\ClientDevice;
use RzSDK\Device\ClientIp;
use RzSDK\DatabaseSpace\DbUserTable;
use RzSDK\Validation\BuildValidationRules;
use RzSDK\HTTPRequest\UserRegistrationRequest;
use RzSDK\HTTPResponse\LaunchResponse;
use RzSDK\Model\User\Registration\UserInfoDatabaseModel;
use RzSDK\Model\User\Registration\UserRegistrationDatabaseModel;
use RzSDK\Model\User\Registration\UserPasswordDatabaseModel;
use RzSDK\Model\User\Registration\UserRegistrationRequestModel;
use RzSDK\Identification\UniqueIntId;
use RzSDK\SqlQueryBuilder\SqlQueryBuilder;
use RzSDK\DateTime\DateDiffType;
use RzSDK\Service\Listener\ServiceListener;
use RzSDK\Response\InfoTypeExtension;
use RzSDK\Service\Adapter\User\Registration\UserRegistrationRequestValidationService;
use RzSDK\Service\Adapter\User\Registration\UserRegistrationCurlUserFetchService;
use RzSDK\DateTime\DateTime;
use RzSDK\Model\User\Registration\UserInfoCurlResponseModel;
use RzSDK\Service\Adapter\User\Registration\UserRegistrationUserRegistrationDatabaseService;
use RzSDK\DatabaseSpace\UserRegistrationTable;
use RzSDK\Service\Adapter\User\Registration\UserRegistrationUserInfoDatabaseService;
use RzSDK\DatabaseSpace\UserInfoTable;
use RzSDK\Service\Adapter\User\Registration\UserRegistrationUserPasswordDatabaseService;
use RzSDK\DatabaseSpace\UserPasswordTable;
use RzSDK\DatabaseSpace\UserLoginAuthLogTable;
use RzSDK\Service\Adapter\User\Registration\UserAuthenticationTokenDatabaseService;
use RzSDK\Model\User\Registration\UserRegistrationResponseModel;
use RzSDK\Log\DebugLog;

?>
<?php
class UserRegistration {
    //
    //private UserRegistrationRequestModel $userRegiRequestModel;
    private UserRegistrationRequest $userRegiRequest;
    private $postedDataSet;
    //
    public function __construct() {
        /* DebugLog::log($_SERVER["HTTP_USER_AGENT"]);
        DebugLog::log($_SERVER); */
        //$userRegiRequestModel = new UserRegistrationRequestModel();
        $this->execute();
    }

    public function execute() {
        if(!empty($_POST)) {
            $this->postedDataSet = $_POST;
            $registrationRequestValidationAction = new class($this) implements ServiceListener {
                private UserRegistration $outerInstance;

                public function __construct($outerInstance) {
                    $this->outerInstance = $outerInstance;
                }

                public function onError($dataSet, $message) {
                    $this->outerInstance->response(null, $message, InfoType::ERROR, $dataSet);
                }

                public function onSuccess($dataSet, $message) {
                    $this->outerInstance->fetchRegisteredDatabaseUser($dataSet);
                }
            };
            (new UserRegistrationRequestValidationService($registrationRequestValidationAction))
                ->execute($this->postedDataSet);
            //
            //$this->response(null, "Successful registration completed", InfoType::SUCCESS, $dataModel);
        }
    }

    public function fetchRegisteredDatabaseUser(UserRegistrationRequest $userRegiRequest) {
        $this->userRegiRequest = $userRegiRequest;
        //$postedDataSet = $this->userRegiRequest->getQuery();
        //DebugLog::log($postedDataSet);
        (new UserRegistrationCurlUserFetchService(
            new class($this) implements ServiceListener {
                private UserRegistration $outerInstance;

                public function __construct($outerInstance) {
                    $this->outerInstance = $outerInstance;
                }

                public function onError($dataSet, $message) {
                    $this->outerInstance->response(null, $message, InfoType::ERROR, $dataSet);
                }

                public function onSuccess($dataSet, $message) {
                    $this->outerInstance->insertIntoUserRegistration($dataSet);
                }
            }
        ))->execute($userRegiRequest, $this->postedDataSet);
    }

    public function insertIntoUserRegistration(UserInfoCurlResponseModel $userInfoCurlResponseModel) {
        //DebugLog::log($userInfoCurlResponseModel);
        //
        if($userInfoCurlResponseModel->infoType == InfoType::DB_DATA_NOT_FOUND) {
            (new UserRegistrationUserRegistrationDatabaseService(
                new class($this) implements ServiceListener {
                    private UserRegistration $outerInstance;

                    public function __construct($outerInstance) {
                        $this->outerInstance = $outerInstance;
                    }

                    public function onError($dataSet, $message) {
                        $this->outerInstance->response(null, $message, InfoType::ERROR, $dataSet);
                    }

                    public function onSuccess($dataSet, $message) {
                        $this->outerInstance->insertIntoUserInfo($dataSet[0]);
                    }
                }
            ))->execute($this->userRegiRequest, $this->postedDataSet);
            return;
        }
        $this->response(null,
            "User already exists error code " . __LINE__,
            InfoType::ERROR,
            $this->postedDataSet);
    }

    public function insertIntoUserInfo(UserRegistrationTable $userRegiTable) {
        //DebugLog::log($userRegiTable);
        (new UserRegistrationUserInfoDatabaseService(
            new class($this) implements ServiceListener {
                private UserRegistration $outerInstance;

                public function __construct($outerInstance) {
                    $this->outerInstance = $outerInstance;
                }

                public function onError($dataSet, $message) {
                    $this->outerInstance->response(null, $message, InfoType::ERROR, $dataSet);
                }

                public function onSuccess($dataSet, $message) {
                    $this->outerInstance->insertIntoUserPassword($dataSet[0]);
                }
            }
        ))->execute($userRegiTable, $this->postedDataSet);
    }

    public function insertIntoUserPassword(UserInfoTable $userInfoTable) {
        //DebugLog::log($userInfoTable);
        (new UserRegistrationUserPasswordDatabaseService(
            new class($this) implements ServiceListener {
                private UserRegistration $outerInstance;

                public function __construct($outerInstance) {
                    $this->outerInstance = $outerInstance;
                }

                public function onError($dataSet, $message) {
                    $this->outerInstance->response(null, $message, InfoType::ERROR, $dataSet);
                }

                public function onSuccess($dataSet, $message) {
                    $this->outerInstance->insertIntoUserAuthenticationToken($dataSet[0], $dataSet[1]);
                }
            }
        ))->execute($userInfoTable, $this->userRegiRequest, $this->postedDataSet);
    }

    public function insertIntoUserAuthenticationToken(UserInfoTable $userInfoTable, UserPasswordTable $userPasswordTable) {
        (new UserAuthenticationTokenDatabaseService(
            new class($this) implements ServiceListener {
                private UserRegistration $outerInstance;

                public function __construct($outerInstance) {
                    $this->outerInstance = $outerInstance;
                }

                public function onError($dataSet, $message) {
                    $this->outerInstance->response(null, $message, InfoType::ERROR, $dataSet);
                }

                public function onSuccess($dataSet, $message) {
                    $this->outerInstance->sendBackHttpResponse($dataSet[0], $dataSet[1]);
                }
            }
        ))->execute($userInfoTable, $userPasswordTable, $this->userRegiRequest);
    }

    public function sendBackHttpResponse(UserInfoTable $userInfoTable, UserLoginAuthLogTable $userLoginAuthLogTable) {
        //DebugLog::log($userInfoTable);
        //DebugLog::log($userLoginAuthLogTable);
        $userRegiResponseModel = new UserRegistrationResponseModel();
        $userRegiResponseModel->user_id = $userInfoTable->user_id;
        $userRegiResponseModel->user_email = $userInfoTable->email;
        $userRegiResponseModel->user_auth_token = $userLoginAuthLogTable->auth_token;

        $responseDataSet = $userRegiResponseModel->toParameterKeyValue();
        //DebugLog::log($responseDataSet);
        $this->response($responseDataSet,
            "Successful registration completed code " . __LINE__,
            InfoType::SUCCESS,
            $this->postedDataSet);
    }

    public function fetchRegistrationTypeOld(UserRegistrationRequest $userRegiRequest) {
        $this->userRegiRequest = $userRegiRequest;
        $postedDataSet = $this->userRegiRequest->getQuery();
        //DebugLog::log($postedDataSet);
        $enumValue = $this->userRegiRequest->auth_type;
        $userAuthType = UserAuthTypeExtension::getUserAuthTypeByValue($enumValue);
        //
        $userRegiRequestModel = new UserRegistrationRequestModel();
        $userRegiRequestModel->deviceType = $this->userRegiRequest->device_type;
        $userRegiRequestModel->authType = $this->userRegiRequest->auth_type;
        $userRegiRequestModel->agentType = $this->userRegiRequest->agent_type;
        $userRegiRequestModel->email = $this->userRegiRequest->user_email;
        $userRegiRequestModel->password = $this->userRegiRequest->password;
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
    }

    private function registeredByEmail(UserRegistrationRequestModel $userRegiRequestModel, array $postedDataSet) {
        if($this->isExistsUserInDatabase($postedDataSet)) {
            $this->response(null,
                "User already exists",
                InfoType::ERROR,
                $postedDataSet);
            return;
        }
        $this->bindUserRegistrationSqlQuery($userRegiRequestModel);
        $this->response(null,
            "Successful registration completed",
            InfoType::SUCCESS,
            $postedDataSet);
    }

    private function isExistsUserInDatabase($postedDataSet) {
        $url = dirname(ROOT_URL) . "/user-info/user-info.php";
        //$dataModel = $userRegiRequestModel->toArrayKeyMapping($userRegiRequestModel);
        //DebugLog::log($postedDataSet);
        $curl = new Curl($url);
        $result = $curl->exec(true, $postedDataSet) . "";
        $result = json_decode($result, true);
        //DebugLog::log($result);
        if(!is_array($result)) {
            return false;
        }
        $responseData = json_decode($result["body"], true);
        //DebugLog::log($responseData);
        if(empty($responseData["body"])) {
            //echo "False Data";
            return false;
        }
        $responseInfoType = $responseData["info"]["type"];
        //DebugLog::log($responseInfoType);
        $responseType = InfoTypeExtension::getInfoTypeByValue($responseInfoType);
        //DebugLog::log($responseType);
        if($responseType == InfoType::ERROR) {
            return false;
        }
        //echo "True Data";
        return true;
    }

    private function bindUserRegistrationSqlQuery($userRegiRequestModel) {
        $uniqueIntId = new UniqueIntId();
        $userId = $uniqueIntId->getId();
        $dateTime = DateTime::getCurrentDateTime();
        //
        $dbConn = $this->getDbConnection();
        //
        $sqlQuery = $this->getInsertUserRegistrationSql($userRegiRequestModel, $userId, $dateTime);
        $this->doRunInsertQuery($dbConn, $sqlQuery);
        $sqlQuery = $this->getInsertUserInfoSql($userRegiRequestModel, $userId, $dateTime);
        $this->doRunInsertQuery($dbConn, $sqlQuery);
        $sqlQuery = $this->getInsertUserPasswordSql($userRegiRequestModel, $userId, $dateTime);
        $this->doRunInsertQuery($dbConn, $sqlQuery);
    }

    private function getInsertUserRegistrationSql($userRegiRequestModel, $userId, $dateTime) {
        $userRegiTable = DbUserTable::$userRegistration;
        $userRegiDatabaseModel = new UserRegistrationDatabaseModel();
        $userRegiDbDataSet = $userRegiDatabaseModel->getInsertSql($userRegiRequestModel, $userId, $dateTime);
        $sqlQueryBuilder = new SqlQueryBuilder();
        $sqlQuery = $sqlQueryBuilder->insert($userRegiTable)
            ->values($userRegiDbDataSet)
            ->build();
        //DebugLog::log($sqlQuery);
        return $sqlQuery;
    }

    private function getInsertUserInfoSql($userRegiRequestModel, $userId, $dateTime) {
        $userInfoTable = DbUserTable::$userInfo;
        $userInfoDatabaseModel = new UserInfoDatabaseModel();
        $userInfoDbDataSet = $userInfoDatabaseModel->getInsertSql($userRegiRequestModel, $userId, $dateTime);
        $sqlQueryBuilder = new SqlQueryBuilder();
        $sqlQuery = $sqlQueryBuilder->insert($userInfoTable)
            ->values($userInfoDbDataSet)
            ->build();
        //DebugLog::log($sqlQuery);
        return $sqlQuery;
    }

    private function getInsertUserPasswordSql($userRegiRequestModel, $userId, $dateTime) {
        $userPasswordTable = DbUserTable::$userPassword;
        $userPasswordDatabaseModel = new UserPasswordDatabaseModel();
        $userPasswordDbDataSet = $userPasswordDatabaseModel->getInsertSql($userRegiRequestModel, $userId, $dateTime);
        $sqlQueryBuilder = new SqlQueryBuilder();
        $sqlQuery = $sqlQueryBuilder->insert($userPasswordTable)
            ->values($userPasswordDbDataSet)
            ->build();
        //DebugLog::log($sqlQuery);
        return $sqlQuery;
    }

    private function doRunInsertQuery($dbConn, $sqlQuery) {
        return $dbConn->query($sqlQuery);
    }

    private function getDbConnection() {
        $dbFullPath = "../" . DB_PATH . "/" . DB_FILE;
        return new SqliteConnection($dbFullPath);
    }

    public function response($body, $message, InfoType $infoType, $parameter = null) {
        $launchResponse = new LaunchResponse();
        $launchResponse->setBody($body)
            ->setInfo($message, $infoType)
            ->setParameter($parameter)
            ->execute();
    }

    /*private function doUserResitrationOld(UserRegistrationRequestModel $userRegiRequestModel, $paramData) {
        $dbFullPath = "../" . DB_PATH . "/" . DB_FILE;
        $connection = new SqliteConnection($dbFullPath);
        //$dataModel = $userRegiRequestModel->toArrayKeyMapping($userRegiRequestModel);
        $userId = time();
        $dateTime = date("Y-m-d H:i:s");
        $clientDevice = new ClientDevice();
        /-* DebugLog::log($_SERVER["HTTP_USER_AGENT"]);
        DebugLog::log($_SERVER); *-/
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
    }*/
}
?>
<?php
$userRegistration = new UserRegistration();
?>
<?php
//https://reintech.io/blog/php-password-hashing-securely-storing-verifying-passwords
?>
