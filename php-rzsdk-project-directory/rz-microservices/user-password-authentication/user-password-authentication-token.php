<?php
require_once("../include.php");
require_once("include.php");
?>
<?php
use RzSDK\Model\User\Authentication\UserAuthenticationRequestModel;
use RzSDK\Model\User\Authentication\UserAuthenticationDatabaseModel;
use RzSDK\Model\User\Authentication\UserAuthenticationTokenDatabaseModel;
use RzSDK\Model\User\Authentication\UserAuthenticationTokenModel;
use RzSDK\Log\DebugLog;
?>
<?php
class UserPasswordAuthenticationToken {
    private UserAuthenticationRequestModel $userAuthRequestModel;
    private UserAuthenticationDatabaseModel $userAuthDatabaseModel;
    private $postedDataSet;

    public function __construct(UserAuthenticationRequestModel $userAuthRequestModel, UserAuthenticationDatabaseModel $userAuthDatabaseModel, $postedDataSet) {
        //$this->userAuthRequestModel = $userAuthRequestModel->toTypeCasting($userAuthRequestModel);
        $this->userAuthRequestModel = $userAuthRequestModel;
        $this->userAuthDatabaseModel = $userAuthDatabaseModel;
        $this->postedDataSet = $postedDataSet;
        //DebugLog::log($this->userAuthRequestModel);
        //DebugLog::log($this->userAuthDatabaseModel);
        $this->execute();
    }

    public function execute() {
        $userAuthTokenDbModel = new UserAuthenticationTokenDatabaseModel();
        $insertSqlData = $userAuthTokenDbModel->getInsertSqlData($this->userAuthRequestModel, $this->userAuthDatabaseModel);
        DebugLog::log($insertSqlData);
    }

    private function response($body, Info $info, $parameter = null) {
        $response = new Response();
        $response->body         = $body;
        $response->info         = $info;
        $response->parameter    = $parameter;
        echo $response->toJson();
    }

    private function extra_01() {
        $userAuthTokenModel = new UserAuthenticationTokenModel();
        /*$userAuthTokenModel->user_id = $this->userAuthDatabaseModel->userId;
        $userAuthTokenModel->expiry_date = $userAuthTokenModel->getExpiryDate();
        $userAuthTokenModel->expiry_time = $userAuthTokenModel->getDateToTime($userAuthTokenModel->expiry_date);*/
        $userAuthTokenModel->setClassProperty($this->userAuthDatabaseModel->userId);
        $idTokenKey = $userAuthTokenModel->getIdTokenKeyPrepare();
        //DebugLog::log($idTokenKey);
        $separator = $userAuthTokenModel->getIdTokenKeySeparator($idTokenKey[1]);
        //DebugLog::log($separator);
        /*$expiryTime = $userAuthTokenModel->getExpiryTime();
        DebugLog::log($expiryTime);*/
    }
}
?>
