<?php
namespace RzSDK\Model\User\Authentication;
?>
<?php
use RzSDK\Model\User\Authentication\UserAuthenticationTokenModel;
use RzSDK\DatabaseSpace\UserLoginAuthLogTable;
use RzSDK\Device\ClientDevice;
use RzSDK\Encryption\EncryptionType;
use RzSDK\Encryption\EncryptionTypeExtension;
use RzSDK\Identification\RandomIdGenerator;
use RzSDK\Encryption\McryptCipherIvGenerator;
use RzSDK\Encryption\JwtManager;
use RzSDK\Encryption\OpensslEncryption;
use RzSDK\Log\DebugLog;
?>
<?php
class UserAuthenticationTokenDatabaseModel {
    public function getInsertSqlData(UserAuthenticationRequestModel $userAuthRequestModel, UserAuthenticationDatabaseModel $userAuthDatabaseModel) {
        $userAuthTokenModel = new UserAuthenticationTokenModel();
        $userLoginAuthLogTable = new UserLoginAuthLogTable();
        $clientDevice = new ClientDevice();
        //
        $userAuthTokenModel->setClassProperty($userAuthDatabaseModel->userId);
        //
        $userAuthTokenList = $this->genUserAuthenticationToken($userAuthDatabaseModel, $userAuthTokenModel);
        //DebugLog::log(EncryptionTypeExtension::getEncryptionTypeNameList());
        $encryptionTypeList = EncryptionTypeExtension::getEncryptionTypeNameList();
        shuffle($encryptionTypeList);
        $encryptionType = EncryptionTypeExtension::getEncryptionTypeByName($encryptionTypeList[0]);
        $mcryptKey = RandomIdGenerator::getRandomString(56);
        $secretKey = McryptCipherIvGenerator::opensslRandomIv();
        $mcryptIvBase64 = rtrim(base64_encode($secretKey), "=");
        $userAuthToken = "";
        if($encryptionType == EncryptionType::JWT_TOKEN) {
            $jwtManager = new JwtManager($secretKey);
            $userAuthToken = $jwtManager->createToken($userAuthTokenList);
        } else {
            $userAuthTokenJson = json_encode($userAuthTokenList);
            $userAuthToken = OpensslEncryption::encryptData($userAuthTokenJson, $mcryptKey, $secretKey);
            $userAuthToken = rtrim($userAuthToken, "=");
            /*$authTokenDecrypt = OpensslEncryption::decryptData($userAuthToken, $mcryptKey, $secretKey);
            DebugLog::log($authTokenDecrypt);*/
        }
        //DebugLog::log($mcryptIvBase64);
        //
        $userLoginAuthLogTable->user_id         = $userAuthDatabaseModel->userId;
        $userLoginAuthLogTable->status          = true;
        $userLoginAuthLogTable->assigned_date   = $userAuthTokenModel->started_date;
        $userLoginAuthLogTable->expired_date    = $userAuthTokenModel->expiry_date;
        $userLoginAuthLogTable->encrypt_type    = $encryptionType->value;
        $userLoginAuthLogTable->mcrypt_key      = $mcryptKey;
        $userLoginAuthLogTable->mcrypt_iv       = $mcryptIvBase64;
        $userLoginAuthLogTable->auth_token      = $userAuthToken;
        $userLoginAuthLogTable->device_type     = $userAuthRequestModel->deviceType;
        $userLoginAuthLogTable->auth_type       = $userAuthRequestModel->authType;
        $userLoginAuthLogTable->agent_type      = $userAuthRequestModel->agentType;
        $userLoginAuthLogTable->regi_os         = $clientDevice->getOs();
        $userLoginAuthLogTable->regi_device     = $clientDevice->getDevice();
        $userLoginAuthLogTable->regi_browser    = $clientDevice->getBrowser();
        $userLoginAuthLogTable->regi_ip         = $userAuthTokenModel->getClientIp();
        $userLoginAuthLogTable->regi_http_agent = $clientDevice->getHttpAgent();
        $userLoginAuthLogTable->modified_by     = $userAuthDatabaseModel->userId;
        $userLoginAuthLogTable->created_by      = $userAuthDatabaseModel->userId;
        $userLoginAuthLogTable->modified_date   = $userAuthTokenModel->started_date;
        $userLoginAuthLogTable->created_date    = $userAuthTokenModel->started_date;
        return $userLoginAuthLogTable;
    }

    public function genUserAuthenticationToken(UserAuthenticationDatabaseModel $userAuthDatabaseModel, UserAuthenticationTokenModel $userAuthTokenModel) {
        $idTokenKey = $userAuthTokenModel->getIdTokenKeyPrepare();
        $currentDateTime = $userAuthTokenModel->getDate();
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
?>