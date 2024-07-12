<?php
namespace RzSDK\Service\Adapter\User\Info;
?>
<?php
/*require_once("../include.php");
require_once("include.php");*/
?>
<?php
use RzSDK\Service\Listener\ServiceListener;
use RzSDK\HTTPRequest\UserInfoRequest;
use RzSDK\DatabaseSpace\DbUserTable;
use RzSDK\Database\SqliteConnection;
use RzSDK\SqlQueryBuilder\SqlQueryBuilder;
use RzSDK\DatabaseSpace\UserInfoTable;
use RzSDK\Utils\ObjectPropertyWizard;
use RzSDK\Log\DebugLog;
?>
<?php
class UserInfoUserInfoDatabaseService {
    private ServiceListener $serviceListener;

    public function __construct(ServiceListener $serviceListener) {
        $this->serviceListener = $serviceListener;
    }

    public function execute(UserInfoRequest $userInfoRequest, $requestDataSet) {
        //DebugLog::log($userInfoRequest);
        $userInfoTableName = DbUserTable::$userInfo;
        $userInfoTable = new UserInfoTable();
        $userInfoTable->email = ObjectPropertyWizard::getVariableName();
        $userEmail = $userInfoTable->email;
        //
        $sqlQueryBuilder = new SqlQueryBuilder();
        $sqlQuery = $sqlQueryBuilder->select()
            ->from($userInfoTableName, "")
            ->where("", array($userEmail => $userInfoRequest->user_email))
            ->build();
        //DebugLog::log($sqlQuery);
        $dbConn = $this->getDbConnection();
        $dbResult = "";
        $dbResult = $this->doRunSelectQuery($dbConn, $sqlQuery);
        if(empty($dbResult)) {
            $this->serviceListener->onError($requestDataSet, "Error! contact with developer error code " . __LINE__);
            return;
        }
        $this->bindDbResultToModel($dbResult, $userInfoRequest, $requestDataSet);
    }

    private function bindDbResultToModel($dbResult, UserInfoRequest $userInfoRequest, $requestDataSet) {
        $userInfoTable = new UserInfoTable();
        $userInfoTableColumns = $userInfoTable->getColumnWithKey();
        //DebugLog::log($userInfoTableColumns);
        $counter = 0;
        foreach($dbResult as $row) {
            foreach($userInfoTableColumns as $key => $value) {
                if(array_key_exists($key, $row)) {
                    $userInfoTable->$key = $row[$key];
                }
            }
            $counter++;
        }
        //$counter = 0;
        if($counter < 1) {
            $this->serviceListener->onSuccess(null, "Error! user not found error code " . __LINE__);
            return;
        }
        //DebugLog::log($userInfoTable);
        $this->serviceListener->onSuccess($userInfoTable, "Successfully User found code " . __LINE__);
    }

    private function doRunSelectQuery($dbConn, $sqlQuery) {
        return $dbConn->query($sqlQuery);
    }

    private function getDbConnection() {
        $dbFullPath = DB_FULL_PATH;
        return new SqliteConnection($dbFullPath);
    }
}
?>