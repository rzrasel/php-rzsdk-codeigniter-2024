<?php
namespace RzSDK\Model\User\Authentication;
?>
<?php
use RzSDK\DatabaseSpace\UserInfoTable;
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
}
?>