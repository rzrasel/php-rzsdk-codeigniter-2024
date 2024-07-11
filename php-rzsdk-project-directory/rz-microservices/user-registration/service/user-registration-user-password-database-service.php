<?php
namespace RzSDK\Service\Adapter\User\Registration;
?>
<?php
/*require_once("../include.php");
require_once("include.php");*/
?>
<?php
use RzSDK\Service\Listener\ServiceListener;
use RzSDK\Model\User\Registration\UserPasswordDatabaseModel;
use RzSDK\DatabaseSpace\UserInfoTable;
use RzSDK\HTTPRequest\UserRegistrationRequest;
use RzSDK\DatabaseSpace\UserPasswordTable;
use RzSDK\Database\SqliteConnection;
use RzSDK\DatabaseSpace\DbUserTable;
use RzSDK\SqlQueryBuilder\SqlQueryBuilder;
use RzSDK\Log\DebugLog;
?>
<?php
class UserRegistrationUserPasswordDatabaseService {
    private ServiceListener $serviceListener;

    public function __construct(ServiceListener $serviceListener) {
        $this->serviceListener = $serviceListener;
    }

    public function execute(UserInfoTable $userInfoTable, UserRegistrationRequest $userRegiRequest, $requestDataSet) {
        //DebugLog::log($userInfoTable);
        $userPasswordDatabaseModel = new UserPasswordDatabaseModel();
        $userPasswordTable = $userPasswordDatabaseModel->getUserPasswordDbInsertDataSet($userInfoTable, $userRegiRequest);
        //DebugLog::log($userPasswordTable);
        $this->modelToUserInsertSqlQuery($userInfoTable, $userPasswordTable);
    }

    private function modelToUserInsertSqlQuery(UserInfoTable $userInfoTable, UserPasswordTable $userPasswordTable) {
        $userPasswordColumn = $userPasswordTable->getColumnWithKey();
        //DebugLog::log($userPasswordColumn);
        $userPasswordTableName = DbUserTable::$userPassword;
        $sqlQueryBuilder = new SqlQueryBuilder();
        $sqlQuery = $sqlQueryBuilder->insert($userPasswordTableName)
            ->values($userPasswordColumn)
            ->build();
        //DebugLog::log($sqlQuery);
        $dbCon = $this->getDbConnection();
        //$dbResult = "";
        $dbResult = $this->doExecuteQuery($dbCon, $sqlQuery);
        $this->serviceListener->onSuccess(
            array(
                $userInfoTable,
                $userPasswordTable,
                $dbResult,
            ), "Successfully user password created into database.");
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