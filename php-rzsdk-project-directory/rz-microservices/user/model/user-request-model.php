<?php
namespace RzSDK\Model\User;
?>
<?php
class UserRequestModel {
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
        $keyMapping = $this->propertyKeyMapping();
        //$key = array_keys($keyMapping);
        $values = array_values($keyMapping);
        /* return array(
            "deviceType"    => "device_type",
            "authType"      => "auth_type",
            "agentType"     => "agent_type",
            "email"         => "user_email",
            "password"      => "password",
        ); */

        return array(
            "deviceType"    => $values[0],
            "authType"      => $values[1],
            "agentType"     => $values[2],
            "email"         => $values[3],
            "password"      => $values[4],
        );
    }

    public function propertyKeyMapping() {
        /* $keyMapping = $this->arrayKeyMap();
        $key = array_keys($keyMapping);
        $values = array_values($keyMapping); */
        return array(
            "device_type"   => "deviceType",
            "auth_type"     => "authType",
            "agent_type"    => "agentType",
            "user_email"    => "email",
            "password"      => "password",
        );
    }
}
?>