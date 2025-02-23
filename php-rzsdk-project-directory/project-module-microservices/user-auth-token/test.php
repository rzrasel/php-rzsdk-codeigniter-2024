<?php
require_once("include.php");
?>
<?php
use RzSDK\Model\User\Authentication\UserAuthTokenModel;
use RzSDK\DatabaseSpace\UserLoginAuthLogTable;
use RzSDK\Device\ClientDevice;
use RzSDK\Encryption\EncryptionTypeExtension;
use RzSDK\Identification\RandomIdGenerator;
use RzSDK\Encryption\McryptCipherIvGenerator;
use RzSDK\Encryption\EncryptionType;
use RzSDK\Encryption\JwtManager;
use RzSDK\Encryption\OpensslEncryption;
use RzSDK\Log\DebugLog;
?>
<?php
class UserAuthTokenHelperModel {
    public $auth_token;
    public function getUserAuthToken() {
        $userAuthTokenModel = new UserAuthTokenModel();
        $clientDevice = new ClientDevice();

        $userAuthTokenModel->setClassProperty("aklfjaslf");
        //
        $userAuthTokenList = $this->genUserAuthTokenKeys($userAuthTokenModel);
        //DebugLog::log($userAuthTokenList);
        //
        //
        $encryptionTypeList = EncryptionTypeExtension::getEncryptionTypeNameList();
        shuffle($encryptionTypeList);
        $encryptionType = EncryptionTypeExtension::getEncryptionTypeByName($encryptionTypeList[0]);
        $mcryptKey = RandomIdGenerator::getRandomString(56);
        $mcryptKey = McryptCipherIvGenerator::hashIv($mcryptKey, 56);
        $secretKey = McryptCipherIvGenerator::opensslRandomIv();
        $mcryptIvBase64 = rtrim(base64_encode($secretKey), "=");
        if($encryptionType == EncryptionType::JWT_TOKEN) {
            $jwtManager = new JwtManager($secretKey);
            $this->auth_token = $jwtManager->createToken($userAuthTokenList);
        } else {
            $userAuthTokenJson = json_encode($userAuthTokenList);
            $this->auth_token = OpensslEncryption::encryptData($userAuthTokenJson, $mcryptKey, $secretKey);
            $this->auth_token = rtrim($this->auth_token, "=");
            /*$authTokenDecrypt = OpensslEncryption::decryptData($userAuthToken, $mcryptKey, $secretKey);
            DebugLog::log($authTokenDecrypt);*/
        }
        DebugLog::log($this->auth_token);
        //
        DebugLog::log($userAuthTokenModel);
    }
    public function genUserAuthTokenKeys(UserAuthTokenModel $userAuthTokenModel) {
        $idTokenKey = $userAuthTokenModel->getIdTokenKeyPrepare();
        $currentDateTime = $userAuthTokenModel->getCurrentDate();
        return array(
            "user_token_id" => $idTokenKey[1],
            "user_ip" => $userAuthTokenModel->getClientIp(),
            "token_start_date" => $userAuthTokenModel->started_date,
            "token_start_time" => $userAuthTokenModel->started_time,
            "token_expiry_date" => $userAuthTokenModel->expiry_date,
            "token_expiry_time" => $userAuthTokenModel->expiry_time,
            "token_refresh_date" => $currentDateTime,
            "token_refresh_time" => $userAuthTokenModel->getDateToTime($currentDateTime),
            "user_token_key" => $idTokenKey[0],
        );
    }
}
$userAuthTokenHelperModel = new UserAuthTokenHelperModel();
$userAuthTokenHelperModel->getUserAuthToken();
?>