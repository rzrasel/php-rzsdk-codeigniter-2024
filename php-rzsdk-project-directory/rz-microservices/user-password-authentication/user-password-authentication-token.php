<?php
require_once("../include.php");
require_once("include.php");
?>
<?php
use RzSDK\Model\User\Authentication\UserAuthenticationRequestModel;
use RzSDK\Model\User\Authentication\UserAuthenticationDatabaseModel;
use RzSDK\Model\User\Authentication\UserAuthenticationTokenDatabaseModel;
use RzSDK\Model\User\Authentication\UserAuthenticationTokenModel;
use RzSDK\Database\SqliteConnection;
use RzSDK\SqlQueryBuilder\SqlQueryBuilder;
use RzSDK\DatabaseSpace\DbUserTable;
use RzSDK\Response\Response;
use RzSDK\HTTPResponse\LaunchResponse;
use RzSDK\Response\Info;
use RzSDK\Response\InfoType;
use RzSDK\DatabaseSpace\UserLoginAuthLogTable;
use RzSDK\Log\DebugLog;
?>
<?php
class UserPasswordAuthenticationToken {
    private UserAuthenticationRequestModel $userAuthRequestModel;
    private UserAuthenticationDatabaseModel $userAuthDatabaseModel;
    private $postedDataSet;

    public function __construct(UserAuthenticationRequestModel $userAuthRequestModel, UserAuthenticationDatabaseModel $userAuthDatabaseModel, $postedDataSet) {
        //$this->userAuthRequestModel = $userAuthRequestModel->toTypeCasting($userAuthRequestModel);
        $this->userAuthRequestModel = $userAuthRequestModel;
        $this->userAuthDatabaseModel = $userAuthDatabaseModel;
        $this->postedDataSet = $postedDataSet;
        //DebugLog::log($this->userAuthRequestModel);
        //DebugLog::log($this->userAuthDatabaseModel);
        $this->execute();
    }

    public function execute() {
        $userAuthTokenDbModel = new UserAuthenticationTokenDatabaseModel();
        $dbConn = $this->getDbConnection();
        //
        $sqlQuery = $this->getSelectSqlQuery($userAuthTokenDbModel);
        //DebugLog::log($sqlQuery);
        $dbResult = $this->doRunDatabaseQuery($dbConn, $sqlQuery);
        $userAuthTokenDbDataSet = $userAuthTokenDbModel->fillDbUserPasswordAuthToken($dbResult);
        //DebugLog::log($userAuthTokenDbDataSet);
        //
        $responseAuthToken = "";
        if(empty($userAuthTokenDbDataSet)) {
            $sqlQuery = $this->getInsertSqlQuery($userAuthTokenDbModel);
            //DebugLog::log($sqlQuery);
            $this->doRunDatabaseQuery($dbConn, $sqlQuery);
            $responseAuthToken = $userAuthTokenDbModel->auth_token;
        } else {
            $isExpired = $userAuthSetDataSet = $userAuthTokenDbModel->isUserAuthTokenExpired($userAuthTokenDbDataSet);
            if($isExpired) {
                $sqlQuery = $this->getUpdateSqlQuery($userAuthTokenDbModel, $userAuthTokenDbDataSet, $isExpired);
                //DebugLog::log($sqlQuery);
                $this->doRunDatabaseQuery($dbConn, $sqlQuery);
                $userAuthTokenDbModel = new UserAuthenticationTokenDatabaseModel();
                $sqlQuery = $this->getInsertSqlQuery($userAuthTokenDbModel);
                //DebugLog::log($sqlQuery);
                $this->doRunDatabaseQuery($dbConn, $sqlQuery);
                $responseAuthToken = $userAuthTokenDbModel->auth_token;
                //DebugLog::log($responseAuthToken);
                //$responseAuthToken = $userAuthTokenDbDataSet->auth_token;
            } else {
                $sqlQuery = $this->getUpdateSqlQuery($userAuthTokenDbModel, $userAuthTokenDbDataSet, $isExpired);
                //DebugLog::log($sqlQuery);
                $this->doRunDatabaseQuery($dbConn, $sqlQuery);
                $responseAuthToken = $userAuthTokenDbDataSet->auth_token;
            }
        }
        $responseDataList = $this->userAuthDatabaseModel->getColumnWithKey();
        $responseDataList["password"] = $this->userAuthRequestModel->password;
        $responseDataList["user_auth_token"] = $responseAuthToken;
        $this->response($responseDataList, "Successful user found", InfoType::SUCCESS, $this->postedDataSet);
    }

    private function getSelectSqlQuery(UserAuthenticationTokenDatabaseModel $userAuthTokenDbModel) {
        $userAuthTokenDbSet = $userAuthTokenDbModel->getSelectWhereSqlData($this->userAuthRequestModel, $this->userAuthDatabaseModel);
        //DebugLog::log($userAuthTokenDbSet);
        $userLoginAuthLogTable = DbUserTable::$userLoginAuthLog;
        $sqlQueryBuilder = new SqlQueryBuilder();
        $sqlQuery = $sqlQueryBuilder
            ->select()
            ->from($userLoginAuthLogTable)
            ->where("", $userAuthTokenDbSet)
            ->limit(1)
            ->offset(0)
            ->orderBy("expired_date", "DESC")
            ->build();
        //DebugLog::log($sqlQuery);
        return $sqlQuery;
    }

    private function getInsertSqlQuery(UserAuthenticationTokenDatabaseModel $userAuthTokenDbModel) {
        //$userAuthTokenDbModel = new UserAuthenticationTokenDatabaseModel();
        $userAuthTokenDbSet = $userAuthTokenDbModel->getInsertSqlData($this->userAuthRequestModel, $this->userAuthDatabaseModel);
        //DebugLog::log($insertSqlData);
        $userLoginAuthLogTable = DbUserTable::$userLoginAuthLog;
        $sqlQueryBuilder = new SqlQueryBuilder();
        $sqlQuery = $sqlQueryBuilder
            ->insert($userLoginAuthLogTable)
            ->values($userAuthTokenDbSet)
            ->build();
        return $sqlQuery;
    }

    private function getUpdateSqlQuery(UserAuthenticationTokenDatabaseModel $userAuthTokenDbModel, UserLoginAuthLogTable $userLoginAuthLogTable, $isExpired) {
        //getUpdateSetSqlData
        $userAuthSetDataSet = $userAuthTokenDbModel->getUpdateSetSqlData($this->userAuthRequestModel, $this->userAuthDatabaseModel, $isExpired);
        $userAuthWhereDataSet = $userAuthTokenDbModel->getUpdateWhereSqlData($this->userAuthRequestModel, $this->userAuthDatabaseModel, $userLoginAuthLogTable);
        //DebugLog::log($userAuthWhereDataSet);
        $userLoginAuthLogTable = DbUserTable::$userLoginAuthLog;
        $sqlQueryBuilder = new SqlQueryBuilder();
        $sqlQuery = $sqlQueryBuilder
            ->update($userLoginAuthLogTable)
            ->set($userAuthSetDataSet)
            ->where("", $userAuthWhereDataSet)
            ->build();
        return $sqlQuery;
    }

    private function doRunDatabaseQuery($dbConn, $sqlQuery) {
        return $dbConn->query($sqlQuery);
    }

    private function getDbConnection() {
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

    private function extra_01() {
        $userAuthTokenModel = new UserAuthenticationTokenModel();
        /*$userAuthTokenModel->user_id = $this->userAuthDatabaseModel->userId;
        $userAuthTokenModel->expiry_date = $userAuthTokenModel->getExpiryDate();
        $userAuthTokenModel->expiry_time = $userAuthTokenModel->getDateToTime($userAuthTokenModel->expiry_date);*/
        $userAuthTokenModel->setClassProperty($this->userAuthDatabaseModel->userId);
        $idTokenKey = $userAuthTokenModel->getIdTokenKeyPrepare();
        //DebugLog::log($idTokenKey);
        $separator = $userAuthTokenModel->getIdTokenKeySeparator($idTokenKey[1]);
        //DebugLog::log($separator);
        /*$expiryTime = $userAuthTokenModel->getExpiryTime();
        DebugLog::log($expiryTime);*/
    }
}
?>
