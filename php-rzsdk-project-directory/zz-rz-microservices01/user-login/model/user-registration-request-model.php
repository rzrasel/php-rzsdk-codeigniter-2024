<?php
namespace RzSDK\Model\User\Login;
?>
<?php
class UserRegistrationRequestModel {
    public $deviceType  = "device_type";
    public $authType    = "auth_type";
    public $agentType   = "agent_type";
    public $email       = "user_email";
    public $password    = "password";

    public function toArrayKeyMapping($object, $keyMapping = array()) {
        if(empty($keyMapping)) {
            $keyMapping = $this->arrayKeyMap();
        }
        $array = [];
        foreach ($keyMapping as $oldKey => $newKey) {
            if (property_exists($object, $oldKey)) {
                $array[$newKey] = $object->$oldKey;
            }
        }
        return $array;
    }

    private function arrayKeyMap() {
        $keyMapping = [
            "deviceType"    => "device_type",
            "authType"      => "auth_type",
            "agentType"     => "agent_type",
            "email"         => "user_email",
            "password"      => "password",
        ];

        return $keyMapping;
    }
}
?>