<?php
namespace RzSDK\Model\User\Registration;
?>
<?php
use RzSDK\DatabaseSpace\UserRegistrationTable;
use RzSDK\Model\User\Registration\UserRegistrationRequestModel;
use RzSDK\Log\DebugLog;
?>
<?php
class UserRegistrationDatabaseModel {
    /*public $table           = "user_registration";
    public $userId          = "user_regi_id";
    public $modifiedBy      = "modified_by";
    public $createdBy       = "created_by";
    public $modifiedDate    = "modified_date";
    public $createdDate     = "created_date";*/
    //
    public function test($userRegiRequestModel, $databaseDataSet) {
        $userRegiTable = new UserRegistrationTable();
        $databaseColumn = $userRegiTable->getColumn();
        $databaseColumn = $userRegiTable->getColumnWithKey();
        DebugLog::log($databaseColumn);
        DebugLog::log($databaseDataSet);
        $testData = array_merge($databaseColumn, $databaseDataSet);
        DebugLog::log($testData);
    }

    public function test2($userRegiRequestModel, $postedDataSet) {
        if(empty($keyMapping)) {
            $keyMapping = $userRegiRequestModel->arrayKeyMap();
        }
        DebugLog::log($keyMapping);
    }
}
?>