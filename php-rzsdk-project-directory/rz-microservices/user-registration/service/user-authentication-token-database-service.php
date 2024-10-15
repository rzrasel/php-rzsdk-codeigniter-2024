<?php
namespace RzSDK\Service\Adapter\User\Registration;
?>
<?php
/*require_once("../include.php");
require_once("include.php");*/
?>
<?php
use RzSDK\Service\Listener\ServiceListener;
use RzSDK\DatabaseSpace\UserInfoTable;
use RzSDK\HTTPRequest\UserRegistrationRequest;
use RzSDK\DatabaseSpace\UserPasswordTable;
use RzSDK\Model\User\Registration\UserAuthenticationTokenDatabaseModel;
use RzSDK\DatabaseSpace\UserLoginAuthLogTable;
use RzSDK\Database\SqliteConnection;
use RzSDK\DatabaseSpace\DbUserTable;
use RzSDK\SqlQueryBuilder\SqlQueryBuilder;
use RzSDK\Log\DebugLog;
?>
<?php
class UserAuthenticationTokenDatabaseService {
    private ServiceListener $serviceListener;

    public function __construct(ServiceListener $serviceListener) {
        $this->serviceListener = $serviceListener;
    }

    public function execute(UserInfoTable $userInfoTable, UserPasswordTable $userPasswordTable, UserRegistrationRequest $userRegiRequest) {
        $userAuthTokenDatabaseModel = new UserAuthenticationTokenDatabaseModel();
        //
        $userLoginAuthLogTable = $userAuthTokenDatabaseModel->getUserAuthenticationTokenDbInsertDataSet($userInfoTable, $userPasswordTable, $userRegiRequest);
        //
        $this->modelToUserAuthenticationTokenInsertSqlQuery($userInfoTable, $userLoginAuthLogTable);
    }

    private function modelToUserAuthenticationTokenInsertSqlQuery(UserInfoTable $userInfoTable, UserLoginAuthLogTable $userLoginAuthLogTable) {
        //DebugLog::log($userLoginAuthLogTable);
        $userAuthTokenColumn = $userLoginAuthLogTable->getColumnWithKey();
        //DebugLog::log($userAuthTokenColumn);
        $userAuthTokenTableName = DbUserTable::$userLoginAuthLog;
        $sqlQueryBuilder = new SqlQueryBuilder();
        $sqlQuery = $sqlQueryBuilder->insert($userAuthTokenTableName)
            ->values($userAuthTokenColumn)
            ->build();
        //DebugLog::log($sqlQuery);
        $dbCon = $this->getDbConnection();
        $dbResult = "";
        $dbResult = $this->doExecuteQuery($dbCon, $sqlQuery);
        $this->serviceListener->onSuccess(
            array(
                $userInfoTable,
                $userLoginAuthLogTable,
                $dbResult,
            ), "Successfully generate user authentication token into database.");
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