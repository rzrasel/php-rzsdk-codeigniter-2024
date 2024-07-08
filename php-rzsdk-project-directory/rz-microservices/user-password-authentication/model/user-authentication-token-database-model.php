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
    public $auth_token;

    public function getSelectWhereSqlData(UserAuthenticationRequestModel $userAuthRequestModel, UserAuthenticationDatabaseModel $userAuthDatabaseModel) {
        $userLoginAuthLogTable = new UserLoginAuthLogTable();
        $tableColumn = $userLoginAuthLogTable->getColumn();
        return array(
            "user_id" => $userAuthDatabaseModel->userId,
            "status" => true,
        );
    }

    public function fillDbUserPasswordAuthToken($dbResult) {
        $userLoginAuthLogTable = new UserLoginAuthLogTable();
        $columnWithKey = $userLoginAuthLogTable->getColumnWithKey();
        //DebugLog::log($columnWithKey);
        if(!empty($dbResult)) {
            $counter = 0;
            foreach ($dbResult as $row) {
                foreach($columnWithKey as $key => $value) {
                    if(array_key_exists($key, $row)) {
                        $userLoginAuthLogTable->$value = $row[$key];
                    }
                }
                $counter++;
            }
            if($counter < 1) {
                return null;
            }
        }
        return $userLoginAuthLogTable;
    }

    public function getInsertSqlData(UserAuthenticationRequestModel $userAuthRequestModel, UserAuthenticationDatabaseModel $userAuthDatabaseModel) {
        $userAuthTokenModel = new UserAuthenticationTokenModel();
        $userLoginAuthLogTable = new UserLoginAuthLogTable();
        $clientDevice = new ClientDevice();
        //
        $userAuthTokenModel->setClassProperty($userAuthDatabaseModel->userId);
        //
        $userAuthTokenList = $this->genUserAuthenticationToken($userAuthDatabaseModel, $userAuthTokenModel);
        //DebugLog::log($userAuthTokenList);
        //DebugLog::log(EncryptionTypeExtension::getEncryptionTypeNameList());
        $encryptionTypeList = EncryptionTypeExtension::getEncryptionTypeNameList();
        shuffle($encryptionTypeList);
        $encryptionType = EncryptionTypeExtension::getEncryptionTypeByName($encryptionTypeList[0]);
        $mcryptKey = RandomIdGenerator::getRandomString(56);
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
        //DebugLog::log($mcryptIvBase64);
        //
        $userLoginAuthLogTable->user_id         = $userAuthDatabaseModel->userId;
        $userLoginAuthLogTable->user_auth_log_id         = $userAuthTokenModel->user_auth_id;
        $userLoginAuthLogTable->status          = true;
        $userLoginAuthLogTable->assigned_date   = $userAuthTokenModel->started_date;
        $userLoginAuthLogTable->refresh_date    = $userAuthTokenModel->started_date;
        $userLoginAuthLogTable->expired_date    = $userAuthTokenModel->expiry_date;
        $userLoginAuthLogTable->encrypt_type    = $encryptionType->value;
        $userLoginAuthLogTable->mcrypt_key      = $mcryptKey;
        $userLoginAuthLogTable->mcrypt_iv       = $mcryptIvBase64;
        $userLoginAuthLogTable->auth_token      = $this->auth_token;
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

        $databaseColumn = $userLoginAuthLogTable->getColumnWithKey();
        return $databaseColumn;
    }

    public function getUpdateSetSqlData(UserAuthenticationRequestModel $userAuthRequestModel, UserAuthenticationDatabaseModel $userAuthDatabaseModel, $isExpired) {
        //$userLoginAuthLogTable = new UserLoginAuthLogTable();
        $userAuthTokenModel = new UserAuthenticationTokenModel();
        if($isExpired) {
            return array(
                "status" => false,
                "modified_by" => $userAuthDatabaseModel->userId,
                "modified_date" => $userAuthTokenModel->getCurrentDate(),
            );
        }
        return array(
            array(
                "refresh_date" => $userAuthTokenModel->getCurrentDate(),
                "expired_date" => $userAuthTokenModel->getExpiryDate(),
                "modified_by" => $userAuthDatabaseModel->userId,
                "modified_date" => $userAuthTokenModel->getCurrentDate(),
            ),
        );
    }

    public function getUpdateWhereSqlData(UserAuthenticationRequestModel $userAuthRequestModel, UserAuthenticationDatabaseModel $userAuthDatabaseModel, UserLoginAuthLogTable $userLoginAuthLogTable) {
        return array(
            "user_auth_log_id" => $userLoginAuthLogTable->user_auth_log_id,
        );
    }

    public function isUserAuthTokenExpired(UserLoginAuthLogTable $userLoginAuthLogTable) {
        $userAuthTokenModel = new UserAuthenticationTokenModel();
        $currentDate = $userAuthTokenModel->getCurrentDate();
        $expiredDate = $userLoginAuthLogTable->expired_date;
        //$currentDate = "2024-07-16 22:49:34";
        $dateDifference = $userAuthTokenModel->getDateIntervalInSeconds($currentDate, $expiredDate);
        /*DebugLog::log($currentDate);
        DebugLog::log($expiredDate);
        DebugLog::log($dateDifference);*/
        if($dateDifference >= 0) {
            return true;
        }
        return false;
    }

    public function genUserAuthenticationToken(UserAuthenticationDatabaseModel $userAuthDatabaseModel, UserAuthenticationTokenModel $userAuthTokenModel) {
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
?>