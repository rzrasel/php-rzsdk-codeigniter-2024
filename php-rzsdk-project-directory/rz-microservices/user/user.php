<?php
require_once("../include.php");
require_once("include.php");
?>
<?php
use RzSDK\Response\Response;
use RzSDK\Response\Info;
use RzSDK\Response\InfoType;
use RzSDK\Database\SqliteConnection;
use RzSDK\Model\User\UserRegistrationRequestModel;
use RzSDK\User\UserRegistrationRegexValidation;

use function RzSDK\Import\logPrint;

?>
<?php
class User {
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
        $sqlQuery = "SELECT * "
        . "FROM user "
        . "WHERE"
        . " email = '{$userRegiRequestModel->email}'"
        . ";";
        $dbData = array();
        $dbResult = $connection->query($sqlQuery);
        if($dbResult != null) {
            foreach($dbResult as $row) {
                $dbData["user_id"]          = $row["user_id"];
                $dbData["email"]            = $row["email"];
                $dbData["status"]           = $row["status"];
                $dbData["modified_by"]      = $row["modified_by"];
                $dbData["created_by"]       = $row["created_by"];
                $dbData["modified_date"]    = $row["modified_date"];
                $dbData["created_date"]     = $row["created_date"];
            }
            //echo $sqlQuery;
            if(!empty($dbData)) {
                //echo "user table is empty";
                $this->response($dbData, new Info("Successful user found", InfoType::SUCCESS), $dataModel);
                return true;
            }
        }
        //
        $sqlQuery = "SELECT * "
        . "FROM user_registration "
        . "WHERE"
        . " email = '{$userRegiRequestModel->email}'"
        . ";";
        $dbResult = $connection->query($sqlQuery);
        if($dbResult != null) {
            foreach($dbResult as $row) {
                $dbData["user_regi_id"]     = $row["user_regi_id"];
                $dbData["email"]            = $row["email"];
                $dbData["status"]           = $row["status"];
                $dbData["modified_by"]      = $row["modified_by"];
                $dbData["created_by"]       = $row["created_by"];
                $dbData["modified_date"]    = $row["modified_date"];
                $dbData["created_date"]     = $row["created_date"];
            }
            //echo $sqlQuery;
            if(!empty($dbData)) {
                //echo "user_registration table is empty";
                $this->response($dbData, new Info("Successful user found", InfoType::SUCCESS), $dataModel);
                return true;
            }
        }
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
new User();
?>