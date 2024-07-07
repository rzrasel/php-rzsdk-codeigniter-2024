<?php
namespace RzSDK\Model\User\UserInfo;
?>
<?php
use RzSDK\DatabaseSpace\UserInfoTable;
use RzSDK\Log\DebugLog;
?>
<?php
class UserInfoDatabaseModel {
    /*public $fullName        = "full_name";
    public $firstName       = "first_name";
    public $midName         = "mid_name";
    public $lastName        = "last_name";
    public $email           = "email";
    public $phone           = "phone";
    public $dateOfBirth     = "date_of_birth";*/

    public function getSelectSql(UserInfoRequestModel $userRegiRequestModel) {
        $userInfoTable = new UserInfoTable();
        //
        $userInfoTable->user_id = null;
        $userInfoTable->email = $userRegiRequestModel->email;
        $userInfoTable->status = true;
        $userInfoTable->modified_by = null;
        $userInfoTable->created_by = null;

        $userInfoTable->modified_date = null;
        $userInfoTable->created_date = null;
        //
        $databaseColumn = $userInfoTable->getColumnWithKey();
        //DebugLog::log($databaseColumn);
        return $databaseColumn;
    }

    public function getColumnName($coumn) {
        $userInfoTable = new UserInfoTable();
        //DebugLog::log($userInfoTable->getColumn());
        //DebugLog::log($userInfoTable->getColumnWithKey());
        $columnWithKeys = $userInfoTable->getColumnWithKey();
        if(array_key_exists($coumn, $columnWithKeys)) {
            return $columnWithKeys[$coumn];
        }
        return "";
    }

    public function fillDbColumn($dbResult) {
        $userInfoTable = new UserInfoTable();
        //DebugLog::log($userInfoTable->getColumn());
        $columns = $userInfoTable->getColumn();
        $columnWithKeys = $userInfoTable->getColumnWithKey();
        //echo $userInfoTable->user_id;
        if(!empty($dbResult)) {
            $counter = 0;
            foreach($dbResult as $row) {
                //$userInfoTable->user_id = $row["user_id"];
                //$columnWithKeys = array_intersect_key($row, $columnWithKeys);
                //DebugLog::log($result);
                //for($i = 0; $i < count($columns); $i++) {
                foreach($columns as $column) {
                    $userInfoTable->$column = null;
                    if(array_key_exists($column, $row)) {
                        $userInfoTable->$column = $row[$column];
                    }
                }
                $counter++;
            }
            //DebugLog::log($columnWithKeys);
            if($counter < 1) {
                return null;
            }
        } else {
            return null;
        }
        return $userInfoTable;
    }
}
?>