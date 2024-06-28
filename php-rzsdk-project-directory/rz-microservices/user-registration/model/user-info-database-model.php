<?php
namespace RzSDK\Model\User\Registration;
?>
<?php
use RzSDK\DatabaseSpace\UserInfoTable;
use RzSDK\Identification\UniqueIntId;
use RzSDK\Log\DebugLog;
?>
<?php
class UserInfoDatabaseModel {
    public function assignDatabaseData(UserRegistrationRequestModel $userRegiRequestModel) {
        $userInfoTable = new UserInfoTable();
        $uniqueIntId = new UniqueIntId();
        $userId = $uniqueIntId->getId();
        $dateTime = date("Y-m-d H:i:s");
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