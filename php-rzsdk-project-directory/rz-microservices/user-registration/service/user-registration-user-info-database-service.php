<?php
namespace RzSDK\Service\Adapter\User\Registration;
?>
<?php
/*require_once("../include.php");
require_once("include.php");*/
?>
<?php
use RzSDK\Service\Listener\ServiceListener;
use RzSDK\DatabaseSpace\UserRegistrationTable;
use RzSDK\Model\User\Registration\UserInfoDatabaseModel;
use RzSDK\DatabaseSpace\UserInfoTable;
use RzSDK\Database\SqliteConnection;
use RzSDK\DatabaseSpace\DbUserTable;
use RzSDK\SqlQueryBuilder\SqlQueryBuilder;
use RzSDK\Log\DebugLog;
?>
<?php
class UserRegistrationUserInfoDatabaseService {
    private ServiceListener $serviceListener;

    public function __construct(ServiceListener $serviceListener) {
        $this->serviceListener = $serviceListener;
    }

    public function execute(UserRegistrationTable $userRegiTable, $requestDataSet) {
        $userInfoDatabaseModel = new UserInfoDatabaseModel();
        $userInfoTable = $userInfoDatabaseModel->getUserInfoDbInsertDataSet($userRegiTable);
        //DebugLog::log($userRegiTable);
        $this->modelToUserInsertSqlQuery($userInfoTable);
    }

    private function modelToUserInsertSqlQuery(UserInfoTable $userInfoTable) {
        //DebugLog::log($userInfoTable);
        $userInfoColumn = $userInfoTable->getColumnWithKey();
        //DebugLog::log($userInfoColumn);
        $userInfoTableName = DbUserTable::$userInfo;
        $sqlQueryBuilder = new SqlQueryBuilder();
        $sqlQuery = $sqlQueryBuilder->insert($userInfoTableName)
            ->values($userInfoColumn)
            ->build();
        //DebugLog::log($sqlQuery);
        $dbCon = $this->getDbConnection();
        $dbResult = "";
        $dbResult = $this->doExecuteQuery($dbCon, $sqlQuery);
        $this->serviceListener->onSuccess(
            array(
                $userInfoTable,
                $dbResult,
            ), "Successfully inserted user into database.");
    }

    private function doExecuteQuery($dbConn, $sqlQuery) {
        return $dbConn->query($sqlQuery);
    }

    private function getDbConnection() {
        $dbFullPath = DB_FULL_PATH;
        return new SqliteConnection($dbFullPath);
    }
}
?>