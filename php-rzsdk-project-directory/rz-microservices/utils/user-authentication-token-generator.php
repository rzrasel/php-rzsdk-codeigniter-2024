<?php
namespace RzSDK\User\Authentication\Token;
?>
<?php
use RzSDK\Encryption\EncryptionTypeExtension;
use RzSDK\Identification\RandomIdGenerator;
use RzSDK\Encryption\McryptCipherIvGenerator;
use RzSDK\Encryption\EncryptionType;
use RzSDK\Encryption\JwtManager;
use RzSDK\Encryption\OpensslEncryption;
use RzSDK\Log\DebugLog;
use RzSDK\Utils\ArrayUtils;
?>
<?php
class UserAuthenticationTokenGenerator {
    private $user_id = "user_id";
    private $user_auth_id = "user_auth_id";
    private $unique_id = "unique_id";
    private $started_date = "started_date";
    private $started_time = "started_time";
    private $expiry_date = "expiry_date";
    private $expiry_time = "expiry_time";
    private $client_ip = "client_ip";
    //
    public $encrypt_type = "encrypt_type";
    public $mcrypt_key = "mcrypt_key";
    public $mcrypt_iv = "mcrypt_iv";
    public $auth_token = "auth_token";
    //
    private $tokenValues = array();

    public function setUserId($userId): self {
        $this->user_id = $userId;
        return $this;
    }

    public function setUserAuthId($userAuthId): self {
        $this->user_auth_id = $userAuthId;
        return $this;
    }

    public function setUniqueId($uniqueId): self {
        $this->unique_id = $uniqueId;
        return $this;
    }

    public function setStartedDate($startedDate): self {
        $this->started_date = $startedDate;
        $this->started_time = strtotime($startedDate);
        return $this;
    }

    public function setExpiryDate($expiryDate): self {
        $this->expiry_date = $expiryDate;
        $this->expiry_time = strtotime($expiryDate);
        return $this;
    }

    public function setClientIp($clientIp): self {
        $this->client_ip = $clientIp;
        return $this;
    }

    public function setTokenProperty($key, $value): self {
        if(empty($key)) {
            return $this;
        }
        $this->tokenValues[$key] = $value;
        return $this;
    }

    public function setTokenProperties($tokens): self {
        if(empty($tokens)) {
            return $this;
        }
        if(!ArrayUtils::isAssociative($tokens)) {
            return $this;
        }
        //$this->tokenValues = array_combine($this->tokenValues, $tokens);
        $this->tokenValues = array_merge($this->tokenValues, $tokens);
        /*foreach($tokens as $key => $value) {
            $this->setTokenProperty($key, $value);
        }*/
        return $this;
    }

    public function generate(bool $isRandTypeEncryption = true, EncryptionType $encryptionType = EncryptionType::JWT_TOKEN) {
        return $this->buildAuthTokenEncryption($isRandTypeEncryption, $encryptionType);
    }

    private function buildAuthTokenEncryption(bool $isRandTypeEncryption = true, EncryptionType $encryptionType = EncryptionType::JWT_TOKEN) {
        $tokenKeyList = $this->buildAuthTokenKey();
        //
        //$encryptionType = $encryptionType;
        if($isRandTypeEncryption) {
            $encryptionTypeList = EncryptionTypeExtension::getEncryptionTypeNameList();
            shuffle($encryptionTypeList);
            $encryptionType = EncryptionTypeExtension::getEncryptionTypeByName($encryptionTypeList[0]);
        }
        //
        $mcryptKey = RandomIdGenerator::getRandomString(56);
        $mcryptKey = McryptCipherIvGenerator::hashIv($mcryptKey, 56);
        $secretKey = McryptCipherIvGenerator::opensslRandomIv();
        $mcryptIvBase64 = rtrim(base64_encode($secretKey), "=");
        //
        $userAuthToken = "";
        if($encryptionType == EncryptionType::JWT_TOKEN) {
            $jwtManager = new JwtManager($secretKey);
            $userAuthToken = $jwtManager->createToken($tokenKeyList);
        } else {
            $userAuthTokenJson = json_encode($tokenKeyList);
            $userAuthToken = OpensslEncryption::encryptData($userAuthTokenJson, $mcryptKey, $secretKey);
            $userAuthToken = rtrim($userAuthToken, "=");
            /*$authTokenDecrypt = OpensslEncryption::decryptData($userAuthToken, $mcryptKey, $secretKey);
            DebugLog::log($authTokenDecrypt);*/
        }
        //
        $this->encrypt_type = $encryptionType->value;
        $this->mcrypt_key = $mcryptKey;
        $this->mcrypt_iv = $mcryptIvBase64;
        $this->auth_token = $userAuthToken;
        //
        $authToken = array(
            "encrypt_type" => $encryptionType->value,
            "mcrypt_key" => $mcryptKey,
            "mcrypt_iv" => $mcryptIvBase64,
            "auth_token" => $userAuthToken,
        );
        //
        return $authToken;
    }

    private function buildAuthTokenKey() {
        $idTokenKey = $this->getIdTokenKeyPrepare();
        $tokenKeys = array(
            "user_token_id" => $idTokenKey[1],
            "user_ip" => $this->client_ip,
            "token_start_date" => $this->started_date,
            "token_start_time" => $this->started_time,
            "token_expiry_date" => $this->expiry_date,
            "token_expiry_time" => $this->expiry_time,
            "token_refresh_date" => $this->started_date,
            "token_refresh_time" => $this->started_time,
            "user_token_key" => $idTokenKey[0],
        );
        //
        //$tokenKeys = array_merge($tokenKeys, $this->tokenValues);
        if(!empty($this->tokenValues)) {
            $tokenKeys = array_merge($tokenKeys, $this->tokenValues);
            $tempTokenKeys = $tokenKeys;
            $tokenList = array();
            for($i = 0; $i < count($tokenKeys); $i++) {
                $key = array_rand($tempTokenKeys, 1);
                $tokenList[$key] = $tokenKeys[$key];
                unset($tempTokenKeys[$key]);
            }
            $tokenKeys = $tokenList;
        }
        //DebugLog::log($tokenKeys);
        //shuffle($tokenKeys);
        //shuffle($tokenKeys);
        return $tokenKeys;
    }

    private function getIdTokenKeyPrepare() {
        $separator = array(".", "-", "_", "|", "I", "l");
        shuffle($separator);
        $idTokenKey = $this->getIdTokenKey();
        $keys = array_keys($idTokenKey);
        $values = array_values($idTokenKey);
        $spacer = $separator[0];
        $key = implode("", $keys);
        $value = implode($spacer, $values);
        $value = $value . $spacer . rand(1111, 9999);
        return array($key, $value);
    }

    private function getIdTokenKey() {
        $tokenArray = $this->tokenPropertyKeyMapping();
        $idTokenKey = $tokenArray["id_token_key"];
        $arrayData = $this->toIdTokenKeyRandomize($idTokenKey);
        /*DebugLog::log($arrayData);
        $out = array_fill_keys($arrayData, null);
        DebugLog::log($out);
        $out = array_flip($arrayData);
        DebugLog::log($out);*/
        $getColumnWithKey = $this->getColumnWithKey();
        //DebugLog::log($getColumnWithKey);
        foreach($arrayData as $key => $value){
            if(array_key_exists($value, $getColumnWithKey)){
                $arrayData[$key] = $getColumnWithKey[$value];
            }
        }
        //DebugLog::log($arrayData);
        return $arrayData;
    }

    private function toIdTokenKeyRandomize($arrayData) {
        if(ArrayUtils::isAssociative($arrayData)) {
            $arrayKey = array_keys($arrayData);
            shuffle($arrayKey);
            $arrayList = array();
            foreach($arrayKey as $key) {
                $arrayList[$key] = $arrayData[$key];
            }
            return $arrayList;
        } else {
            shuffle($arrayData);
            return $arrayData;
        }
    }

    private function getColumnWithKey() {
        /*return array_intersect_key(
            get_object_vars($this),
            get_mangled_object_vars($this)
        );*/
        return array(
            "user_id" => $this->user_id,
            "user_auth_id" => $this->user_auth_id,
            "unique_id" => $this->unique_id,
            "started_date" => $this->started_date,
            "started_time" => $this->started_time,
            "expiry_date" => $this->expiry_date,
            "expiry_time" => $this->expiry_time,
            "client_ip" => $this->client_ip,
        );
    }

    private function tokenPropertyKeyMapping() {
        return array(
            "id_token_key" => array(
                "a" => "user_auth_id",
                "e" => "expiry_time",
                "i" => "user_id",
                "s" => "started_time",
                "u" => "unique_id",
            ),
        );
    }
}
?>