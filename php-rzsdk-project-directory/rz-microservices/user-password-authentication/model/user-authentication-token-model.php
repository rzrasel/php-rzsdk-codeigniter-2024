<?php
namespace RzSDK\Model\User\Authentication;
?>
<?php
use RzSDK\Utils\ArrayUtils;
use RzSDK\Device\ClientDevice;
use RzSDK\DateTime\DateTime;
use RzSDK\Device\ClientIp;
use RzSDK\DateTime\DateDiffType;
use RzSDK\Identification\UniqueIntId;
use RzSDK\Log\DebugLog;
?>
<?php
class UserAuthenticationTokenModel {
    public $user_id;
    public $unique_id;
    public $started_date;
    public $started_time;
    public $expiry_date;
    public $expiry_time;
    private $expiryPriod = 7;
    private DateDiffType $expiryType = DateDiffType::days;

    public function setExpiryPriod($expiryPriod = 7) {
        $this->expiryPriod = $expiryPriod;
    }

    public function setExpiryType($expiryType = DateDiffType::days) {
        $this->expiryType = $expiryType;
    }

    public function getUniqueId() {
        $uniqueIntId = new UniqueIntId();
        return $uniqueIntId->getId();
    }

    public function getDate() {
        return DateTime::getCurrentDateTime();
    }

    public function getDateToTime($date) {
        return DateTime::getDateToTime($date);
    }

    public function getExpiryDate($add = 7, DateDiffType $dateDiffType = DateDiffType::days) {
        $toDate = DateTime::getCurrentDateTime();
        return DateTime::addDateTime($toDate, $add, $dateDiffType);
    }

    public function getExpiryTime($add = 7, DateDiffType $dateDiffType = DateDiffType::days) {
        $expiryDate = $this->getExpiryDate($add, $dateDiffType);
        return DateTime::getDateToTime($expiryDate);
    }

    public function getClientIp() {
        return ClientIp::ip();
    }

    public function setClassProperty($userId) {
        $toDate = DateTime::getCurrentDateTime();
        $this->user_id = $userId;
        $this->unique_id = $this->getUniqueId();
        $this->started_date = $toDate;
        $this->started_time = DateTime::getDateToTime($toDate);
        $this->expiry_date = $toDate;
        $this->expiry_time = DateTime::getDateToTime($toDate);
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

    public function getIdTokenKeySeparator($idTokenKey) {
        $separator = substr($idTokenKey, -5);
        return substr($separator, 0, 1);
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
                "e" => "expiry_time",
                "i" => "user_id",
                "s" => "started_time",
                "u" => "unique_id",
            ),
        );
    }
}
?>