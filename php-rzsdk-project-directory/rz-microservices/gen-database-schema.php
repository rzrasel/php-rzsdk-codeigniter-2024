<?php
namespace RzSDK\Generator;
?>
<?php
use RzSDK\DatabaseSpace\DbType;
use RzSDK\DatabaseSpace\UserLoginAuthLogTable;
use RzSDK\DatabaseSpace\UserLoginAuthLogTableQuery;
use RzSDK\DatabaseSpace\UserRegistrationTableQuery;
use RzSDK\DatabaseSpace\UserInfoTableQuery;
use RzSDK\DatabaseSpace\UserPasswordTableQuery;
use RzSDK\Log\DebugLog;
?>
<?php
class GenDatabaseSchema {
    public function __construct($isPrint = true) {
        if($isPrint) {
            $this->execute();
        }
    }

    private function execute() {
        $dbType = DbType::SQLITE;
        $userRegistration = new UserRegistrationTableQuery($dbType);
        $userInfo = new UserInfoTableQuery();
        $userPassword = new UserPasswordTableQuery();
        $userLoginAuthLogTable = new UserLoginAuthLogTableQuery($dbType);

        $sqlQuery = "";
        //
        // Database drop sql query
        $sqlQuery .= $userRegistration->dropQuery();
        $sqlQuery .= "<br />";
        $sqlQuery .= $userInfo->dropQuery($dbType);
        $sqlQuery .= "<br />";
        $sqlQuery .= $userPassword->dropQuery($dbType);
        $sqlQuery .= "<br />";
        $sqlQuery .= $userLoginAuthLogTable->dropQuery();
        $sqlQuery .= "<br />";
        //
        // Database sql query
        $sqlQuery .= "<br />";
        $sqlQuery .= $userRegistration->execute();
        $sqlQuery .= "<br />";
        $sqlQuery .= "<br />";
        $sqlQuery .= $userInfo->execute($dbType);
        $sqlQuery .= "<br />";
        $sqlQuery .= "<br />";
        $sqlQuery .= $userPassword->execute($dbType);
        $sqlQuery .= "<br />";
        $sqlQuery .= $userLoginAuthLogTable->execute();
        $sqlQuery .= "<br />";
        //
        // Database delete sql query
        $sqlQuery .= "<br />";
        $sqlQuery .= $userRegistration->deleteQuery();
        $sqlQuery .= "<br />";
        $sqlQuery .= $userInfo->deleteQuery($dbType);
        $sqlQuery .= "<br />";
        $sqlQuery .= $userPassword->deleteQuery($dbType);
        $sqlQuery .= "<br />";
        $sqlQuery .= $userLoginAuthLogTable->deleteQuery();
        $sqlQuery .= "<br />";
        //
        // Print database sql query
        DebugLog::log($sqlQuery);
    }
}
?>