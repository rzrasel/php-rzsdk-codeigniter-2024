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
use RzSDK\Database\SqliteConnection;
use RzSDK\DatabaseSpace\DbUserTable;
use RzSDK\DatabaseSpace\UserRegistrationTable;
use RzSDK\Utils\ObjectPropertyWizard;
use RzSDK\SqlQueryBuilder\SqlQueryBuilder;
use RzSDK\Log\DebugLog;
?>
<?php
class UserInfoUserRegistrationDatabaseService {
    private ServiceListener $serviceListener;

    public function __construct(ServiceListener $serviceListener) {
        $this->serviceListener = $serviceListener;
    }

    public function execute(UserInfoRequest $userInfoRequest, $requestDataSet) {
        $userRegistrationTableName = DbUserTable::$userRegistration;
        $userRegistrationTable = new UserRegistrationTable();
        $userRegistrationTable->email = ObjectPropertyWizard::getVariableName();
        $userEmail = $userRegistrationTable->email;
        //
        $sqlQueryBuilder = new SqlQueryBuilder();
        $sqlQuery = $sqlQueryBuilder->select()
            ->from($userRegistrationTableName, "")
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
        $userRegistrationTable = new UserRegistrationTable();
        $userRegistrationTableColumns = $userRegistrationTable->getColumnWithKey();
        //DebugLog::log($userRegistrationTableColumns);
        $counter = 0;
        foreach($dbResult as $row) {
            foreach($userRegistrationTableColumns as $key => $value) {
                if(array_key_exists($key, $row)) {
                    $userRegistrationTable->$key = $row[$key];
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
        $this->serviceListener->onSuccess($userRegistrationTable, "Successfully User found code " . __LINE__);
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