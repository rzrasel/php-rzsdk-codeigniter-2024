<?php
namespace RzSDK\Generator;
?>
<?php
use RzSDK\DatabaseSpace\DbType;
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
        $sqlQuery = "";
        $dbType = DbType::SQLITE;
        $userRegistration = new UserRegistrationTableQuery();
        $userInfo = new UserInfoTableQuery();
        $userPassword = new UserPasswordTableQuery();

        $sqlQuery = "";
        $sqlQuery .= $userRegistration->dropQuery($dbType);
        $sqlQuery .= "<br />";
        $sqlQuery .= $userInfo->dropQuery($dbType);
        $sqlQuery .= "<br />";
        $sqlQuery .= $userPassword->dropQuery($dbType);
        $sqlQuery .= "<br />";
        $sqlQuery .= "<br />";
        $sqlQuery .= $userRegistration->execute($dbType);
        $sqlQuery .= "<br />";
        $sqlQuery .= "<br />";
        $sqlQuery .= $userInfo->execute($dbType);
        $sqlQuery .= "<br />";
        $sqlQuery .= "<br />";
        $sqlQuery .= $userPassword->execute($dbType);
        $sqlQuery .= "<br />";
        $sqlQuery .= "<br />";
        $sqlQuery .= $userRegistration->deleteQuery($dbType);
        $sqlQuery .= "<br />";
        $sqlQuery .= $userInfo->deleteQuery($dbType);
        $sqlQuery .= "<br />";
        $sqlQuery .= $userPassword->deleteQuery($dbType);
        $sqlQuery .= "<br />";
        DebugLog::log($sqlQuery);
    }
}
?>