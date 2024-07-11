<?php
namespace RzSDK\Model\User\Registration;
?>
<?php
use RzSDK\Identification\UniqueIntId;
use RzSDK\DateTime\DateTime;
use RzSDK\Device\ClientDevice;
use RzSDK\Device\ClientIp;
use RzSDK\DatabaseSpace\UserInfoTable;
use RzSDK\DatabaseSpace\UserPasswordTable;
use RzSDK\HTTPRequest\UserRegistrationRequest;
use RzSDK\DatabaseSpace\UserLoginAuthLogTable;
use RzSDK\Encryption\EncryptionTypeExtension;
use RzSDK\Identification\RandomIdGenerator;
use RzSDK\Encryption\McryptCipherIvGenerator;
use RzSDK\Encryption\EncryptionType;
use RzSDK\Encryption\JwtManager;
use RzSDK\Encryption\OpensslEncryption;
use RzSDK\User\Authentication\Token\UserAuthenticationTokenKeyListGenerator;
use RzSDK\Utils\ArrayUtils;
use RzSDK\Log\DebugLog;
?>
<?php
class UserAuthenticationTokenDatabaseModel {
    public function getUserAuthenticationTokenDbInsertDataSet(UserInfoTable $userInfoTable, UserPasswordTable $userPasswordTable, UserRegistrationRequest $userRegiRequest): UserLoginAuthLogTable {
        //
        $uniqueIntId = new UniqueIntId();
        $userId = $uniqueIntId->getId();
        $expiredDate = DateTime::addDateTime($userInfoTable->created_date, USER_TOKEN_EXPIRED_DAY);
        //
        $clientDevice = new ClientDevice();
        $userLoginAuthLogTable = new UserLoginAuthLogTable();
        //
        $userLoginAuthLogTable->user_id = $userInfoTable->user_id;
        $userLoginAuthLogTable->user_auth_log_id = $userId;
        $userLoginAuthLogTable->status = true;
        $userLoginAuthLogTable->is_activate = true;
        $userLoginAuthLogTable->assigned_date = $userInfoTable->created_date;

        $userLoginAuthLogTable->refresh_date = $userInfoTable->created_date;
        $userLoginAuthLogTable->expired_date = $expiredDate;
        $userLoginAuthLogTable->encrypt_type = $userInfoTable->user_id;
        $userLoginAuthLogTable->mcrypt_key = $userInfoTable->user_id;
        $userLoginAuthLogTable->mcrypt_iv = $userInfoTable->user_id;

        $userLoginAuthLogTable->auth_token = $userInfoTable->user_id;
        $userLoginAuthLogTable->device_type = $userRegiRequest->device_type;
        $userLoginAuthLogTable->auth_type = $userRegiRequest->auth_type;
        $userLoginAuthLogTable->agent_type = $userRegiRequest->agent_type;
        $userLoginAuthLogTable->regi_os = $clientDevice->getOs();

        $userLoginAuthLogTable->regi_device = $clientDevice->getDevice();
        $userLoginAuthLogTable->regi_browser = $clientDevice->getBrowser();
        $userLoginAuthLogTable->regi_ip = ClientIp::ip();
        $userLoginAuthLogTable->regi_http_agent = $clientDevice->getHttpAgent();
        $userLoginAuthLogTable->modified_by = $userInfoTable->created_by;

        $userLoginAuthLogTable->created_by = $userInfoTable->created_by;
        $userLoginAuthLogTable->modified_date = $userInfoTable->created_date;
        $userLoginAuthLogTable->created_date = $userInfoTable->created_date;
        //
        $userLoginAuthLogTable = $this->setUserAuthenticationTokenDataSet($userLoginAuthLogTable);
        //
        //$databaseColumn = $userLoginAuthLogTable->getColumnWithKey();
        //DebugLog::log($databaseColumn);
        return $userLoginAuthLogTable;
    }

    public function setUserAuthenticationTokenDataSet(UserLoginAuthLogTable $userLoginAuthLogTable): UserLoginAuthLogTable {
        //
        $uniqueIntId = new UniqueIntId();
        $userId = $uniqueIntId->getId();
        $userAuthTokenKeyListGen = new UserAuthenticationTokenKeyListGenerator();
        $userAuthTokenKeyListGen
            ->setUserId($userLoginAuthLogTable->user_id)
            ->setUserAuthId($userLoginAuthLogTable->user_auth_log_id)
            ->setUniqueId($userId)
            ->setStartedDate($userLoginAuthLogTable->assigned_date)
            ->setExpiryDate($userLoginAuthLogTable->expired_date)
            ->setClientIp($userLoginAuthLogTable->regi_ip);
        $userAuthTokenKeyList = $userAuthTokenKeyListGen->genUserAuthenticationTokenList();
        //DebugLog::log($userAuthTokenKeyList);
        //
        $encryptionTypeList = EncryptionTypeExtension::getEncryptionTypeNameList();
        shuffle($encryptionTypeList);
        $encryptionType = EncryptionTypeExtension::getEncryptionTypeByName($encryptionTypeList[0]);
        //
        $mcryptKey = RandomIdGenerator::getRandomString(56);
        $mcryptKey = McryptCipherIvGenerator::hashIv($mcryptKey, 56);
        $secretKey = McryptCipherIvGenerator::opensslRandomIv();
        $mcryptIvBase64 = rtrim(base64_encode($secretKey), "=");
        $userAuthToken = "";
        if($encryptionType == EncryptionType::JWT_TOKEN) {
            $jwtManager = new JwtManager($secretKey);
            $userAuthToken = $jwtManager->createToken($userAuthTokenKeyList);
        } else {
            $userAuthTokenJson = json_encode($userAuthTokenKeyList);
            $userAuthToken = OpensslEncryption::encryptData($userAuthTokenJson, $mcryptKey, $secretKey);
            $userAuthToken = rtrim($userAuthToken, "=");
            /*$authTokenDecrypt = OpensslEncryption::decryptData($userAuthToken, $mcryptKey, $secretKey);
            DebugLog::log($authTokenDecrypt);*/
        }
        //
        $userLoginAuthLogTable->encrypt_type = $encryptionType->value;
        $userLoginAuthLogTable->mcrypt_key = $mcryptKey;
        $userLoginAuthLogTable->mcrypt_iv = $mcryptIvBase64;
        $userLoginAuthLogTable->auth_token = $userAuthToken;
        //DebugLog::log($mcryptIvBase64);
        //
        return $userLoginAuthLogTable;
    }
}
?>