<?php
require_once("../include.php");
require_once("include.php");
?>
<?php
use RzSDK\Response\Response;
use RzSDK\HTTPResponse\LaunchResponse;
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
use RzSDK\Model\User\Authentication\UserAuthenticationDatabaseModel;
use RzSDK\SqlQueryBuilder\SqlQueryBuilder;
use RzSDK\Log\DebugLog;

?>
<?php
class UserAuthentication {
    private UserAuthenticationRequestModel $userAuthRequestModel;
    private UserAuthenticationDatabaseModel $userAuthDatabaseModel;
    //

    public function __construct() {
        $this->execute();
    }

    private function doDatabaseTask($userAuthenticationRequestModel, $postedDataSet) {
        /*DebugLog::log($userAuthenticationRequestModel);
        DebugLog::log($userAuthenticationRequestModel->arrayKeyMap());*/

        /*$userInfoTable = DbUserTable::$userInfo;
        $userInfoAlias = "user";
        $userPasswordTable = DbUserTable::$userPassword;
        $userPasswordAlias = "password";
        $UserAuthDatabaseModel = new UserAuthenticationDatabaseModel();
        $userInfoUserId = $UserAuthDatabaseModel->getUserInfoColumnName("user_id");
        $userInfoEmail = $UserAuthDatabaseModel->getUserInfoColumnName("email");
        $userInfoStatus = $UserAuthDatabaseModel->getUserInfoColumnName("status");
        $userPasswordUserId = $UserAuthDatabaseModel->getUserPasswordColumnName("user_id");
        $userPasswordStatus = $UserAuthDatabaseModel->getUserPasswordColumnName("status");
        $userInfoWhereEmail = "{$userInfoEmail} = '{$userAuthenticationRequestModel->email}'";
        $userInfoWhereStatus = "{$userInfoStatus} = TRUE";
        $userPasswordWhereStatus = "{$userPasswordStatus} = TRUE";
        $sqlQueryBuilder = new SqlQueryBuilder();
        $sqlQuery = $sqlQueryBuilder->select()
            ->from(array($userInfoTable => $userInfoAlias))
            ->innerJoin(array($userInfoTable => $userInfoAlias), array($userPasswordTable => $userPasswordAlias), $userInfoUserId, $userPasswordUserId)
            ->where($userInfoAlias, array($userInfoWhereEmail))
            ->where($userInfoAlias, array($userInfoWhereStatus))
            ->where($userPasswordAlias, array($userPasswordWhereStatus))
            ->build();
        DebugLog::log($sqlQuery);*/
    }

    public function execute() {
        if(!empty($_POST)) {
            //DebugLog::log($_POST);
            $isValidated = $this->isValidated($_POST);
            if(!$isValidated["is_validate"]) {
                return;
            }
            $this->userAuthRequestModel = $isValidated["data_set"];
            //DebugLog::log($userRegiRequestModel);
            $postedDataSet = $this->userAuthRequestModel->toArrayKeyMapping($this->userAuthRequestModel);
            //DebugLog::log($postedDataSet);
            //$this->doDatabaseTask($userAuthenticationRequestModel, $postedDataSet);

            $enumValue = $this->userAuthRequestModel->authType;
            $userAuthType = getUserAuthTypeByValue($enumValue);

            if(!empty($userAuthType)) {
                if($userAuthType == UserAuthType::EMAIL) {
                    $this->userAuthenticationByEmail($this->userAuthRequestModel, $postedDataSet);
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
        $this->userAuthRequestModel = new UserAuthenticationRequestModel();
        $keyMapping = $this->userAuthRequestModel->propertyKeyMapping();
        //DebugLog::log($keyMapping);
        $isValidated = true;
        foreach($authParamList as $value) {
            //Extract requested values from $_POST
            if(array_key_exists($value, $requestDataSet)) {
                $paramValue = $requestDataSet[$value];
                $this->userAuthRequestModel->{$keyMapping[$value]} = $paramValue;
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
            "data_set"          => $this->userAuthRequestModel,
        );
    }

    private function userAuthenticationByEmail(UserAuthenticationRequestModel $userAuthRequestModel, $postedDataSet) {
        $this->userAuthDatabaseModel = $this->getDbUserAuthentication($userAuthRequestModel);
        if(empty($this->userAuthDatabaseModel)) {
            //DebugLog::log("debug_log_print");
            $this->response(null, "Error user not found", InfoType::INFO, $postedDataSet);
            return false;
        }

        $postedPassword = $userAuthRequestModel->password;
        $hashedPassword = $this->userAuthDatabaseModel->password;
        if(!$this->isPasswordVerified($postedPassword, $hashedPassword)) {
            $this->response(null, "Error user not found", InfoType::INFO, $postedDataSet);
            return false;
        }
        /*else {
            $userAuthDataSet->password = $postedPassword;
            $this->response($this->userAuthDatabaseModel, new Info("Successful user found", InfoType::SUCCESS), $postedDataSet);
            return true;
        }*/
        //DebugLog::log($this->userAuthDatabaseModel);
        $userPasswordAuthToken = new UserPasswordAuthenticationToken($this->userAuthRequestModel, $this->userAuthDatabaseModel, $postedDataSet);
    }

    private function getDbUserAuthentication($userAuthenticationRequestModel) {
        //return $this->getDbUser($userAuthenticationRequestModel);
        $dbConn = $this->getDbConnection();
        //
        $this->userAuthDatabaseModel = new UserAuthenticationDatabaseModel();
        //$userAuthenticationRequestModel->email = $userAuthenticationRequestModel->email . "(ajsfdjf)";
        $sqlQuery = $this->getUserAuthSql($userAuthenticationRequestModel);
        //DebugLog::log($sqlQuery);
        $dbResult = $this->doRunSelectQuery($dbConn, $sqlQuery);
        $this->userAuthDatabaseModel = $this->userAuthDatabaseModel->fillDbUserAuthentication($dbResult);
        //DebugLog::log($this->userAuthDatabaseModel);
        return $this->userAuthDatabaseModel;
    }

    private function getUserAuthSql($userAuthenticationRequestModel) {
        $userInfoTable = DbUserTable::$userInfo;
        $userInfoAlias = "user";
        $userPasswordTable = DbUserTable::$userPassword;
        $userPasswordAlias = "password";
        $userAuthDatabaseModel = new UserAuthenticationDatabaseModel();
        $userInfoUserId = $userAuthDatabaseModel->getUserInfoColumnName("user_id");
        $userInfoEmail = $userAuthDatabaseModel->getUserInfoColumnName("email");
        $userInfoStatus = $userAuthDatabaseModel->getUserInfoColumnName("status");
        $userPasswordUserId = $userAuthDatabaseModel->getUserPasswordColumnName("user_id");
        $userPasswordStatus = $userAuthDatabaseModel->getUserPasswordColumnName("status");
        $userInfoWhereEmail = "{$userInfoEmail} = '{$userAuthenticationRequestModel->email}'";
        $userInfoWhereStatus = "{$userInfoStatus} = TRUE";
        $userPasswordWhereStatus = "{$userPasswordStatus} = TRUE";
        $sqlQueryBuilder = new SqlQueryBuilder();
        $sqlQuery = $sqlQueryBuilder->select()
            ->from(array($userInfoTable => $userInfoAlias))
            ->innerJoin(array($userInfoTable => $userInfoAlias), array($userPasswordTable => $userPasswordAlias), $userInfoUserId, $userPasswordUserId)
            ->where($userInfoAlias, array($userInfoWhereEmail, $userInfoWhereStatus))
            ->where($userPasswordAlias, array($userPasswordWhereStatus))
            ->build();
        //DebugLog::log($sqlQuery);
        return $sqlQuery;
    }

    private function doRunSelectQuery($dbConn, $sqlQuery) {
        return $dbConn->query($sqlQuery);
    }

    private function getDbConnection() {
        $dbFullPath = "../" . DB_PATH . "/" . DB_FILE;
        return new SqliteConnection($dbFullPath);
    }

    private function isPasswordVerified($requestedPassword, $hashedPassword) {
        if(password_verify($requestedPassword, $hashedPassword)) {
            return true;
        }
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
$userAuthentication = new UserAuthentication();
?>
