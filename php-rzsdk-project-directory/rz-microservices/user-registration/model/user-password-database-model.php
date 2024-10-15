<?php
namespace RzSDK\Model\User\Registration;
?>
<?php
use RzSDK\DatabaseSpace\UserPasswordTable;
use RzSDK\DatabaseSpace\UserInfoTable;
use RzSDK\HTTPRequest\UserRegistrationRequest;
use RzSDK\Log\DebugLog;
?>
<?php
class UserPasswordDatabaseModel {
    public function getInsertSql(UserRegistrationRequestModel $userRegiRequestModel, $userId, $dateTime) {
        $userPasswordTable = new UserPasswordTable();
        $password = password_hash($userRegiRequestModel->password, PASSWORD_DEFAULT);
        //
        $userPasswordTable->user_id = $userId;
        $userPasswordTable->password = $password;
        $userPasswordTable->status = true;
        $userPasswordTable->modified_by = $userId;
        $userPasswordTable->created_by = $userId;

        $userPasswordTable->modified_date = $dateTime;
        $userPasswordTable->created_date = $dateTime;
        //
        $databaseColumn = $userPasswordTable->getColumnWithKey();
        //DebugLog::log($databaseColumn);
        return $databaseColumn;
    }

    public function getUserPasswordDbInsertDataSet(UserInfoTable $userInfoTable, UserRegistrationRequest $userRegiRequest): UserPasswordTable {
        $userPasswordTable = new UserPasswordTable();
        $password = password_hash($userRegiRequest->password, PASSWORD_DEFAULT);
        //
        $userPasswordTable->user_id = $userInfoTable->user_id;
        $userPasswordTable->password = $password;
        $userPasswordTable->status = true;
        $userPasswordTable->modified_by = $userInfoTable->user_id;
        $userPasswordTable->created_by = $userInfoTable->user_id;

        $userPasswordTable->modified_date = $userInfoTable->created_date;
        $userPasswordTable->created_date = $userInfoTable->created_date;
        //
        //$databaseColumn = $userPasswordTable->getColumnWithKey();
        //DebugLog::log($databaseColumn);
        return $userPasswordTable;
    }
}
?>