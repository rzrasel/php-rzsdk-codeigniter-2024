<?php
namespace RzSDK\Model\User\Registration;
?>
<?php
use RzSDK\DatabaseSpace\UserRegistrationTable;
use RzSDK\HTTPRequest\UserRegistrationRequest;
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
        //DebugLog::log($keyMapping);
        //
        //
        $requestedDataSet = [];
        foreach($keyMapping as $oldKey => $newKey) {
            if (property_exists($userRegiRequestModel, $oldKey)) {
                $requestedDataSet[$newKey] = $userRegiRequestModel->$oldKey;
            }
        }
        //DebugLog::log($requestedDataSet);
        //
        //
        $userRegiRequest = new UserRegistrationRequest();
        $keyMapping = $userRegiRequest->keyMapping();
        //DebugLog::log($keyMapping);
        $databaseDataSet = [];
        foreach($keyMapping as $oldKey => $newKey) {
            if(array_key_exists($oldKey, $requestedDataSet)) {
                $databaseDataSet[$newKey] = $requestedDataSet[$oldKey];
            }
        }
        //DebugLog::log($databaseDataSet);
        //
        //
        $userRegiTable = new UserRegistrationTable();
        $databaseColumn = $userRegiTable->getColumn();
        $databaseColumn = $userRegiTable->getColumnWithKey();
        //DebugLog::log($databaseColumn);
        //
        /*$keys = array_keys($array);
        $values = array_fill(0, count($keys), null);
        $new_array = array_combine($keys, $values);*/
        $databaseColumn = array_fill_keys(array_keys($databaseColumn), null);
        //DebugLog::log($databaseColumn);
        //
        //
        $testData = array_merge($databaseColumn, $databaseDataSet);
        DebugLog::log($testData);
    }
}
?>