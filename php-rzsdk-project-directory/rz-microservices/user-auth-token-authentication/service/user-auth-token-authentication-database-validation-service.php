<?php
namespace RzSDK\Service\Adapter;
?>
<?php
?>
<?php
use RzSDK\Database\SqliteConnection;
use RzSDK\Service\Listener\ServiceListener;
use RzSDK\HTTPRequest\UserAuthTokenAuthenticationRequest;
use RzSDK\SqlQueryBuilder\SqlQueryBuilder;
use RzSDK\DatabaseSpace\DbUserTable;
use RzSDK\DatabaseSpace\UserLoginAuthLogTable;
use RzSDK\Utils\ObjectPropertyWizard;
use RzSDK\DateTime\DateDiffType;
use RzSDK\DateTime\DateTime;
use RzSDK\Log\DebugLog;
?>
<?php
class UserAuthTokenAuthenticationDatabaseValidationService {
    private ServiceListener $serviceListener;
    private UserLoginAuthLogTable $userLoginAuthLogTable;

    public function __construct(ServiceListener $serviceListener) {
        if($serviceListener != null) {
            $this->serviceListener = $serviceListener;
        }
    }

    public function execute(UserAuthTokenAuthenticationRequest $userAuthTokenAuthRequest, $requestDataSet) {
        //$this->serviceListener->onError($requestDataSet, "Message");
        $sqlQuery = $this->getSelectSqlQuery($userAuthTokenAuthRequest);
        //DebugLog::log($sqlQuery);
        $dbConn = $this->getDbConnection();
        $dbResult = $this->doRunDatabaseQuery($dbConn, $sqlQuery);
        $dbDataSet = $this->fillDatabaseSelectData($dbResult);
        if(empty($dbDataSet)) {
            $this->serviceListener->onError($requestDataSet, "Error! user authentication failed to authenticate error codes " . __LINE__);
            return;
        }
        $this->userLoginAuthLogTable = $dbDataSet;
        //DebugLog::log($this->userLoginAuthLogTable);
        $this->checkUserAuthTokenExpired($this->userLoginAuthLogTable, $requestDataSet);
    }

    private function checkUserAuthTokenExpired(UserLoginAuthLogTable $userLoginAuthLogTable, $requestDataSet) {
        //DebugLog::log($userLoginAuthLogTable->status);
        if($this->isTokenExpired($userLoginAuthLogTable) || !$userLoginAuthLogTable->status) {
            //$this->serviceListener->onError($requestDataSet, "Error! user authentication failed for expiration date error codes " . __LINE__);
            $this->updateActiveStatus($userLoginAuthLogTable);
            $this->findLastActivatedToken($userLoginAuthLogTable, $requestDataSet);
            return;
        }
        $this->doUpdateExpiryDate($userLoginAuthLogTable, $requestDataSet);
        $this->serviceListener->onSuccess($userLoginAuthLogTable, "Successful authentication request code " . __LINE__);
    }//getUpdateExpiryDateSqlQuery

    private function updateActiveStatus(UserLoginAuthLogTable $userLoginAuthLogTable) {
        $sqlQuery = $this->getUpdateSqlQuery($userLoginAuthLogTable);
        //DebugLog::log($sqlQuery);
        $dbConn = $this->getDbConnection();
        $dbResult = $this->doRunDatabaseQuery($dbConn, $sqlQuery);
        return $dbResult;
    }

    private function findLastActivatedToken(UserLoginAuthLogTable $userLoginAuthLogTable, $requestDataSet) {
        //DebugLog::log($userLoginAuthLogTable);
        $sqlQuery = $this->getSelectLastActivateTokenSqlQuery($userLoginAuthLogTable);
        //DebugLog::log($sqlQuery);
        $dbConn = $this->getDbConnection();
        $dbResult = $this->doRunDatabaseQuery($dbConn, $sqlQuery);
        $dbDataSet = $this->fillDatabaseSelectData($dbResult);
        if(empty($dbDataSet)) {
            $this->serviceListener->onError($requestDataSet, "Error! user authentication failed to authenticate error code " . __LINE__);
            return;
        }
        $this->userLoginAuthLogTable = $dbDataSet;
        //DebugLog::log($this->userLoginAuthLogTable);
        if($this->isTokenExpired($this->userLoginAuthLogTable) || !$this->userLoginAuthLogTable->status) {
            $this->serviceListener->onError($requestDataSet, "Error! user authentication failed for expiration date error code " . __LINE__);
            return;
        }
        $this->doUpdateExpiryDate($this->userLoginAuthLogTable, $requestDataSet);
        $this->serviceListener->onSuccess($this->userLoginAuthLogTable, "Successful authentication request code " . __LINE__);
    }

    private function doUpdateExpiryDate(UserLoginAuthLogTable $userLoginAuthLogTable, $requestDataSet) {
        $sqlQuery = $this->getUpdateExpiryDateSqlQuery($userLoginAuthLogTable);
        //DebugLog::log($sqlQuery);
        $dbConn = $this->getDbConnection();
        $dbResult = $this->doRunDatabaseQuery($dbConn, $sqlQuery);
    }

    private function fillDatabaseSelectData($dbResult) {
        $userLoginAuthLogTable = new UserLoginAuthLogTable();
        $userLoginAuthLogTableColumns = $userLoginAuthLogTable->getColumnWithKey();
        //DebugLog::log($userLoginAuthLogTableColumns);
        if(!empty($dbResult)) {
            $counter = 0;
            foreach ($dbResult as $row) {
                foreach($userLoginAuthLogTableColumns as $key => $value) {
                    if(array_key_exists($key, $row)) {
                        $userLoginAuthLogTable->$key = $row[$key];
                    }
                }
                $counter++;
            }
            if($counter < 1) {
                return null;
            }
        }
        //DebugLog::log($userLoginAuthLogTable);
        return $userLoginAuthLogTable;
    }

    private function getSelectSqlQuery(UserAuthTokenAuthenticationRequest $userAuthTokenAuthRequest) {
        $tempAuthLogTable = new UserLoginAuthLogTable();
        $authToken = $tempAuthLogTable->auth_token;
        $isActivate = $tempAuthLogTable->is_activate;
        $expiredDate = $tempAuthLogTable->expired_date;
        $tempAuthLogTable = null;
        $currentDate = DateTime::getCurrentDateTime();
        //DebugLog::log($status);
        $userLoginAuthLogTableName = DbUserTable::$userLoginAuthLog;
        $sqlQueryBuilder = new SqlQueryBuilder();
        $sqlQuery = $sqlQueryBuilder
            ->select()
            ->from($userLoginAuthLogTableName)
            ->where("", array(
                $authToken => $userAuthTokenAuthRequest->user_auth_token,
                $isActivate => "{=} true",
                //$expiredDate => "{>}{$currentDate}",
            ))
            ->build();
        //DebugLog::log($sqlQuery);
        return $sqlQuery;
    }

    private function getUpdateExpiryDateSqlQuery(UserLoginAuthLogTable $userLoginAuthLogTable) {
        $tempAuthLogTable = new UserLoginAuthLogTable();
        $userId = $tempAuthLogTable->user_id;
        $userAuthLogId = $tempAuthLogTable->user_auth_log_id;
        $authToken = $tempAuthLogTable->auth_token;
        $status = $tempAuthLogTable->status;
        $isActivate = $tempAuthLogTable->is_activate;
        $refreshDate = $tempAuthLogTable->refresh_date;
        $expiredDate = $tempAuthLogTable->expired_date;
        $modifiedBy = $tempAuthLogTable->modified_by;
        $modifiedDate = $tempAuthLogTable->modified_date;
        $tempAuthLogTable = null;
        //DebugLog::log($status);
        $currentDate = DateTime::getCurrentDateTime();
        $expiredDateTime = $this->getExpiryDate();
        $userLoginAuthLogTableName = DbUserTable::$userLoginAuthLog;
        $sqlQueryBuilder = new SqlQueryBuilder();
        $sqlQuery = $sqlQueryBuilder
            ->update($userLoginAuthLogTableName)
            ->set(array(
                $refreshDate => $currentDate,
                $expiredDate => $expiredDateTime,
                $modifiedBy => $userLoginAuthLogTable->user_id,
                $modifiedDate => $currentDate,
            ))
            ->where("", array(
                $userId => $userLoginAuthLogTable->user_id,
                $userAuthLogId => $userLoginAuthLogTable->user_auth_log_id,
                //$authToken => $userLoginAuthLogTable->auth_token,
                //$isActivate => "{=} true",
                //$expiredDate => "{<}{$currentDate}",
            ))
            ->build();
        //"modified_by" => $userAuthDatabaseModel->userId,
        //                "modified_date" => $userAuthTokenModel->getCurrentDate(),
        //DebugLog::log($sqlQuery);
        return $sqlQuery;
    }

    private function getUpdateSqlQuery(UserLoginAuthLogTable $userLoginAuthLogTable) {
        $tempAuthLogTable = new UserLoginAuthLogTable();
        $userId = $tempAuthLogTable->user_id;
        $authToken = $tempAuthLogTable->auth_token;
        $status = $tempAuthLogTable->status;
        $isActivate = $tempAuthLogTable->is_activate;
        $expiredDate = $tempAuthLogTable->expired_date;
        $modifiedBy = $tempAuthLogTable->modified_by;
        $modifiedDate = $tempAuthLogTable->modified_date;
        $tempAuthLogTable = null;
        //DebugLog::log($status);
        $currentDate = DateTime::getCurrentDateTime();
        $userLoginAuthLogTableName = DbUserTable::$userLoginAuthLog;
        $sqlQueryBuilder = new SqlQueryBuilder();
        $sqlQuery = $sqlQueryBuilder
            ->update($userLoginAuthLogTableName)
            ->set(array(
                $status => false,
                $modifiedBy => $userLoginAuthLogTable->user_id,
                $modifiedDate => $currentDate,
            ))
            ->where("", array(
                $userId => $userLoginAuthLogTable->user_id,
                //$authToken => $userLoginAuthLogTable->auth_token,
                //$isActivate => "{=} true",
                $expiredDate => "{<}{$currentDate}",
            ))
            ->build();
        //"modified_by" => $userAuthDatabaseModel->userId,
        //                "modified_date" => $userAuthTokenModel->getCurrentDate(),
        //DebugLog::log($sqlQuery);
        return $sqlQuery;
    }

    private function getSelectLastActivateTokenSqlQuery(UserLoginAuthLogTable $userLoginAuthLogTable) {
        $tempAuthLogTable = new UserLoginAuthLogTable();
        $userId = $tempAuthLogTable->user_id;
        $authToken = $tempAuthLogTable->auth_token;
        $status = $tempAuthLogTable->status;
        $isActivate = $tempAuthLogTable->is_activate;
        $expiredDate = $tempAuthLogTable->expired_date;
        $tempAuthLogTable = null;
        $currentDate = DateTime::getCurrentDateTime();
        //DebugLog::log($status);
        $userLoginAuthLogTableName = DbUserTable::$userLoginAuthLog;
        $sqlQueryBuilder = new SqlQueryBuilder();
        $sqlQuery = $sqlQueryBuilder
            ->select()
            ->from($userLoginAuthLogTableName)
            ->where("", array(
                $userId => $userLoginAuthLogTable->user_id,
                $status => "{=} true",
                $isActivate => "{=} true",
                $expiredDate => "{>}{$currentDate}",
            ))
            ->build();
        //DebugLog::log($sqlQuery);
        return $sqlQuery;
    }

    private function isTokenExpired(UserLoginAuthLogTable $userLoginAuthLogTable) {
        $currentDate = DateTime::getCurrentDateTime();
        $expiredDate = $userLoginAuthLogTable->expired_date;
        $dateDifference = DateTime::getIntervalInSeconds($currentDate, $expiredDate);
        //DebugLog::log($userLoginAuthLogTable->status);
        if($dateDifference >= 0) {
            return true;
        }
        return false;
    }

    private function getExpiryDate($add = 7, DateDiffType $dateDiffType = DateDiffType::days) {
        $toDate = DateTime::getCurrentDateTime();
        return DateTime::addDateTime($toDate, $add, $dateDiffType);
    }

    private function doRunDatabaseQuery($dbConn, $sqlQuery) {
        return $dbConn->query($sqlQuery);
    }

    private function getDbConnection() {
        $dbFullPath = "../" . DB_PATH . "/" . DB_FILE;
        return new SqliteConnection($dbFullPath);
    }
}
?>