<?php
namespace RzSDK\Model\User\Authentication;
?>
<?php
use RzSDK\DatabaseSpace\UserInfoTable;
use RzSDK\DatabaseSpace\UserPasswordTable;
use RzSDK\Log\DebugLog;
?>
<?php
class UserAuthenticationDatabaseModel {
    public $userId      = "user_id";
    public $email       = "user_email";
    public $password    = "password";

    public function getUserInfoColumnName($coumn) {
        $userInfoTable = new UserInfoTable();
        //DebugLog::log($userInfoTable->getColumn());
        //DebugLog::log($userInfoTable->getColumnWithKey());
        $columnWithKeys = $userInfoTable->getColumnWithKey();
        if(array_key_exists($coumn, $columnWithKeys)) {
            return $columnWithKeys[$coumn];
        }
        return "";
    }

    public function getUserPasswordColumnName($coumn) {
        $userPasswordTable = new UserPasswordTable();
        //DebugLog::log($userInfoTable->getColumn());
        //DebugLog::log($userInfoTable->getColumnWithKey());
        $columnWithKeys = $userPasswordTable->getColumnWithKey();
        if(array_key_exists($coumn, $columnWithKeys)) {
            return $columnWithKeys[$coumn];
        }
        return "";
    }

    public function fillDbUserAuthentication($dbResult) {
        $retValue = null;
        if(!empty($dbResult)) {
            $counter = 0;
            foreach ($dbResult as $row) {
                $retValue = $this;
                $dbKeyMapping = $this->dbToModelKeyMapping();
                foreach($dbKeyMapping as $key => $value) {
                    $this->$value = null;
                    if(array_key_exists($key, $row)) {
                        $this->$value = $row[$key];
                    }
                }
                $counter++;
            }
            if($counter < 1) {
                return null;
            }
        }
        return $retValue;
    }

    public function getColumn() {
        $result = array_intersect_key(
            get_object_vars($this),
            get_mangled_object_vars($this)
        );
        return array_keys($result);
    }

    public function getColumnWithKey() {
        return array_intersect_key(
            get_object_vars($this),
            get_mangled_object_vars($this)
        );
    }

    public function dbToModelKeyMapping() {
        return array(
            "user_id"   => "userId",
            "email"     => "email",
            "password"  => "password",
        );
    }
}
?>