<?php
namespace RzSDK\User\Authentication\Token;
?>
<?php
use RzSDK\Utils\ArrayUtils;
?>
<?php
class UserAuthenticationTokenKeyListGenerator {
    public $user_id = "user_id";
    public $user_auth_id = "user_auth_id";
    public $unique_id = "unique_id";
    public $started_date = "started_date";
    public $started_time = "started_time";
    public $expiry_date = "expiry_date";
    public $expiry_time = "expiry_time";
    public $client_ip = "client_ip";

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

    public function genUserAuthenticationTokenList() {
        $idTokenKey = $this->getIdTokenKeyPrepare();
        return array(
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
    }

    public function getIdTokenKeyPrepare() {
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

    public function getIdTokenKey() {
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

    public function toIdTokenKeyRandomize($arrayData) {
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

    public function getColumnWithKey() {
        return array_intersect_key(
            get_object_vars($this),
            get_mangled_object_vars($this)
        );
    }

    public function tokenPropertyKeyMapping() {
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