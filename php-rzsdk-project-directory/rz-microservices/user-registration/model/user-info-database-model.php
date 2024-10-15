<?php
namespace RzSDK\Model\User\Registration;
?>
<?php
use RzSDK\DatabaseSpace\UserInfoTable;
use RzSDK\Utils\ObjectPropertyWizard;
use RzSDK\DatabaseSpace\UserRegistrationTable;
use RzSDK\Log\DebugLog;
?>
<?php
class UserInfoDatabaseModel {

    public function getInsertSql(UserRegistrationRequestModel $userRegiRequestModel, $userId, $dateTime) {
        $userInfoTable = new UserInfoTable();
        //
        $userInfoTable->user_id = $userId;
        $userInfoTable->email = $userRegiRequestModel->email;
        $userInfoTable->status = true;
        $userInfoTable->modified_by = $userId;
        $userInfoTable->created_by = $userId;

        $userInfoTable->modified_date = $dateTime;
        $userInfoTable->created_date = $dateTime;
        //
        $databaseColumn = $userInfoTable->getColumnWithKey();
        //DebugLog::log($databaseColumn);
        return $databaseColumn;
    }

    public function getUserInfoDbInsertDataSet(UserRegistrationTable $userRegiTable): UserInfoTable {
        $userInfoTable = new UserInfoTable();
        //
        $userInfoTable->user_id = $userRegiTable->user_regi_id;
        $userInfoTable->email = $userRegiTable->email;
        $userInfoTable->status = true;
        $userInfoTable->modified_by = $userRegiTable->user_regi_id;
        $userInfoTable->created_by = $userRegiTable->user_regi_id;

        $userInfoTable->modified_date = $userRegiTable->created_date;
        $userInfoTable->created_date = $userRegiTable->created_date;
        //
        //$databaseColumn = $userInfoTable->getColumnWithKey();
        //DebugLog::log($databaseColumn);
        return $userInfoTable;
    }
}
?>