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
use RzSDK\User\Authentication\Token\UserAuthenticationTokenGenerator;
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
        $userAuthTokenKeyListGen = new UserAuthenticationTokenGenerator();
        $userAuthTokenKeyListGen
            ->setUserId($userLoginAuthLogTable->user_id)
            ->setUserAuthId($userLoginAuthLogTable->user_auth_log_id)
            ->setUniqueId($userId)
            ->setStartedDate($userLoginAuthLogTable->assigned_date)
            ->setExpiryDate($userLoginAuthLogTable->expired_date)
            ->setTokenProperties(
                array(
                    "device_type" => $userLoginAuthLogTable->device_type,
                    "agent_type" => $userLoginAuthLogTable->agent_type,
                )
            )
            ->setClientIp($userLoginAuthLogTable->regi_ip);
        $userAuthTokenDataSet = $userAuthTokenKeyListGen->generate();
        $userLoginAuthLogTable->encrypt_type = $userAuthTokenKeyListGen->encrypt_type;
        $userLoginAuthLogTable->mcrypt_key = $userAuthTokenKeyListGen->mcrypt_key;
        $userLoginAuthLogTable->mcrypt_iv = $userAuthTokenKeyListGen->mcrypt_iv;
        $userLoginAuthLogTable->auth_token = $userAuthTokenKeyListGen->auth_token;
        //
        return $userLoginAuthLogTable;
    }
}
?>