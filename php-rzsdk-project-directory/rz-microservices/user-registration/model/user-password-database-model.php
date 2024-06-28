<?php
namespace RzSDK\Model\User\Registration;
?>
<?php
use RzSDK\DatabaseSpace\UserPasswordTable;
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
}
?>