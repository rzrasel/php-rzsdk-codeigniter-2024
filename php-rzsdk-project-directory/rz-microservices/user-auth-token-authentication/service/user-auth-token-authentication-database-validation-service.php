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
        $currentDate = DateTime::getCurrentDateTime();
        $expiredDate = $userLoginAuthLogTable->expired_date;
        $dateDifference = DateTime::getIntervalInSeconds($currentDate, $expiredDate);
        if($dateDifference >= 0) {
            $this->serviceListener->onError($requestDataSet, "Error! user authentication failed for expiration date error codes " . __LINE__);
            return;
        }
        $this->serviceListener->onSuccess($userLoginAuthLogTable, "Successful authentication request.");
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
        $userLoginAuthLogTable = new UserLoginAuthLogTable();
        $userLoginAuthLogTable->status = ObjectPropertyWizard::getVariableName();
        $authToken = $userLoginAuthLogTable->auth_token;
        $status = $userLoginAuthLogTable->status;
        $userLoginAuthLogTable = null;
        //DebugLog::log($status);
        $userLoginAuthLogTable = DbUserTable::$userLoginAuthLog;
        $sqlQueryBuilder = new SqlQueryBuilder();
        $sqlQuery = $sqlQueryBuilder
            ->select()
            ->from($userLoginAuthLogTable)
            ->where("", array(
                $authToken => $userAuthTokenAuthRequest->user_auth_token,
                $status => "{=} true",
            ))
            ->build();
        //DebugLog::log($sqlQuery);
        return $sqlQuery;
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