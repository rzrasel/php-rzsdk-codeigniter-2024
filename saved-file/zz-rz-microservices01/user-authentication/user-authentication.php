<?php
require_once("../include.php");
require_once("include.php");
?>
<?php
use RzSDK\Response\Response;
use RzSDK\Response\Info;
use RzSDK\Response\InfoType;
use RzSDK\Database\SqliteConnection;
use RzSDK\Model\User\Authentication\UserRegistrationRequestModel;
use RzSDK\User\Authentication\UserRegistrationRegexValidation;
use RzSDK\DatabaseSpace\DbUserTable;

use RzSDK\Log\DebugLog;

?>
<?php
class UserAuthentication {
    public function __construct() {
        $this->execute();
    }

    public function execute() {
        if(!empty($_POST)) {
            $userRegiRequestModel = new UserRegistrationRequestModel();
            $userRegiRequestModel->agentType = $_POST[$userRegiRequestModel->agentType];
            $userRegiRequestModel->authType = $_POST[$userRegiRequestModel->authType];
            $userRegiRequestModel->deviceType = $_POST[$userRegiRequestModel->deviceType];
            $userRegiRequestModel->email = $_POST[$userRegiRequestModel->email];
            $userRegiRequestModel->password = $_POST[$userRegiRequestModel->password];
            $dataModel = $userRegiRequestModel->toArrayKeyMapping($userRegiRequestModel);

            if(!$this->regexValidation($userRegiRequestModel)) {
                return;
            }
            //DebugLog::log($this->getDbUser($userRegiRequestModel));
            if($this->getDbUser($userRegiRequestModel)) {
                return;
            }
            //$this->response(null, new Info("Successful registration completed", InfoType::SUCCESS), $dataModel);
        }
    }

    private function regexValidation(UserRegistrationRequestModel $userRegiRequestModel) {
        $userRegistrationRegexValidation = new UserRegistrationRegexValidation();
        return $userRegistrationRegexValidation->execute($userRegiRequestModel);
    }

    private function getDbUser(UserRegistrationRequestModel $userRegiRequestModel) {
        $dbFullPath = "../" . DB_PATH . "/" . DB_FILE;
        $dataModel = $userRegiRequestModel->toArrayKeyMapping($userRegiRequestModel);
        $connection = new SqliteConnection($dbFullPath);
        $tableUser = DbUserTable::$userInfo;
        $tableUserPass = DbUserTable::$userPassword;
        $sqlQuery = "SELECT * FROM {$tableUser} AS user "
        . "INNER JOIN {$tableUserPass} AS password "
        . "ON"
        . " user.user_id = password.user_id "
        . "WHERE"
        . " user.email = '{$userRegiRequestModel->email}'"
        . " AND user.status = '1'"
        . " AND password.status = '1'"
        . ";";
        //echo $sqlQuery;
        $dbData = array();
        $dbResult = $connection->query($sqlQuery);
        if($dbResult != null) {
            foreach($dbResult as $row) {
                $dbData["user_id"]  = $row["user_id"];
                $dbData["email"]    = $row["email"];
                $dbData["password"] = $row["password"];
            }
            //DebugLog::log("debug_log_print");
            if(!empty($dbData)) {
                //DebugLog::log("debug_log_print");
                $this->response($dbData, new Info("Successful user found", InfoType::SUCCESS), $dataModel);
                return true;
            } else {
                //DebugLog::log("debug_log_print");
                $this->response($dbData, new Info("Error user not found", InfoType::INFO), $dataModel);
                return false;
            }
        }
        //DebugLog::log("debug_log_print");
        $this->response($dbData, new Info("Error user not found", InfoType::ERROR), $dataModel);
        return false;
    }

    private function response($body, Info $info, $parameter = null) {
        $response = new Response();
        $response->body         = $body;
        $response->info         = $info;
        $response->parameter    = $parameter;
        echo $response->toJson();
    }
}
?>
<?php
$userAuthentication = new UserAuthentication();
?>
