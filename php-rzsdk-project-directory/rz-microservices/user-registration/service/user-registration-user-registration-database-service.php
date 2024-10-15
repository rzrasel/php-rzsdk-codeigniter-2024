<?php
namespace RzSDK\Service\Adapter\User\Registration;
?>
<?php
/*require_once("../include.php");
require_once("include.php");*/
?>
<?php
use RzSDK\Service\Listener\ServiceListener;
use RzSDK\HTTPRequest\UserRegistrationRequest;
use RzSDK\Model\User\Registration\UserRegistrationDatabaseModel;
use RzSDK\Identification\UniqueIntId;
use RzSDK\DateTime\DateTime;
use RzSDK\Database\SqliteConnection;
use RzSDK\DatabaseSpace\UserRegistrationTable;
use RzSDK\DatabaseSpace\DbUserTable;
use RzSDK\SqlQueryBuilder\SqlQueryBuilder;
use RzSDK\Log\DebugLog;
?>
<?php
class UserRegistrationUserRegistrationDatabaseService {
    private ServiceListener $serviceListener;

    public function __construct(ServiceListener $serviceListener) {
        $this->serviceListener = $serviceListener;
    }

    public function execute(UserRegistrationRequest $userRegiRequest, $requestDataSet) {
        $uniqueIntId = new UniqueIntId();
        $userId = $uniqueIntId->getId();
        $dateTime = DateTime::getCurrentDateTime();
        //
        $userRegiDatabaseModel = new UserRegistrationDatabaseModel();
        $userRegiTable = $userRegiDatabaseModel->getUserRegistrationDbInsertDataSet($userRegiRequest, $userId, $dateTime);
        //DebugLog::log($userRegiTable);
        $this->modelToUserInsertSqlQuery($userRegiTable);
    }

    private function modelToUserInsertSqlQuery(UserRegistrationTable $userRegiTable) {
        $userRegiColumn = $userRegiTable->getColumnWithKey();
        //DebugLog::log($userRegiColumn);
        $userRegiTableName = DbUserTable::$userRegistration;
        $sqlQueryBuilder = new SqlQueryBuilder();
        $sqlQuery = $sqlQueryBuilder->insert($userRegiTableName)
            ->values($userRegiColumn)
            ->build();
        //DebugLog::log($sqlQuery);
        $dbCon = $this->getDbConnection();
        $dbResult = "";
        $dbResult = $this->doExecuteQuery($dbCon, $sqlQuery);
        $this->serviceListener->onSuccess(
        array(
            $userRegiTable,
            $dbResult,
        ), "Successfully inserted user into database code " . __LINE__);
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