<?php
namespace RzSDK\Model\User\UserInfo;
?>
<?php
use RzSDK\DatabaseSpace\UserRegistrationTable;
use RzSDK\Log\DebugLog;
?>
<?php
class UserRegistrationDatabaseModel
{
    /*public $fullName        = "full_name";
    public $firstName       = "first_name";
    public $midName         = "mid_name";
    public $lastName        = "last_name";
    public $email           = "email";
    public $phone           = "phone";
    public $dateOfBirth     = "date_of_birth";*/

    /*public function getInsertSql(UserRegistrationRequestModel $userRegiRequestModel, $userId, $dateTime) {
        $userInfoTable = new UserInfoTable();
        //$userRegiTable = new UserRegistrationTable();
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
    }*/

    public function getColumnName($coumn) {
        $userRegiTable = new UserRegistrationTable();
        //DebugLog::log($userRegiTable->getColumn());
        //DebugLog::log($userRegiTable->getColumnWithKey());
        $columnWithKeys = $userRegiTable->getColumnWithKey();
        if(array_key_exists($coumn, $columnWithKeys)) {
            return $columnWithKeys[$coumn];
        }
        return "";
    }

    public function fillDbColumn($dbResult) {
        $userRegiTable = new UserRegistrationTable();
        //DebugLog::log($userRegiTable->getColumn());
        //DebugLog::log($userRegiTable->getColumnWithKey());
        $columns = $userRegiTable->getColumn();
        //$columnWithKeys = $userRegiTable->getColumnWithKey();
        if(!empty($dbResult)) {
            $counter = 0;
            foreach($dbResult as $row) {
                //$userInfoTable->user_id = $row["user_id"];
                //$columnWithKeys = array_intersect_key($row, $columnWithKeys);
                //DebugLog::log($result);
                //for($i = 0; $i < count($columns); $i++) {
                foreach($columns as $column) {
                    $userRegiTable->$column = null;
                    if(array_key_exists($column, $row)) {
                        $userRegiTable->$column = $row[$column];
                    }
                }
                $counter++;
            }
            //DebugLog::log($columnWithKeys);
            if($counter < 1) {
                return null;
            }
        }
        return $userRegiTable;
    }
}
?>