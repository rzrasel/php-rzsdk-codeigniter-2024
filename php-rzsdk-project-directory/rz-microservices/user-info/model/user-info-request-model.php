<?php
namespace RzSDK\Model\User\UserInfo;
?>
<?php
//use RzSDK\DatabaseSpace\UserInfoTable;
use RzSDK\Log\DebugLog;
?>
<?php
class UserInfoRequestModel {
    public $deviceType = "device_type";
    public $authType = "auth_type";
    public $agentType = "agent_type";
    public $email = "user_email";
    public $password = "password";

    public function toArrayKeyMapping($object, $keyMapping = array()) {
        if (empty($keyMapping)) {
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

    /*public function arrayKeyMap() {
        $keyMapping = $this->propertyKeyMapping();
        $key = array_keys($keyMapping);
        //$values = array_values($keyMapping);
        /-* return array(
            "deviceType"    => "device_type",
            "authType"      => "auth_type",
            "agentType"     => "agent_type",
            "email"         => "user_email",
            "password"      => "password",
        ); *-/

        return array(
            "deviceType"    => $key[0],
            "authType"      => $key[1],
            "agentType"     => $key[2],
            "email"         => $key[3],
            "password"      => $key[4],
        );
    }

    public function propertyKeyMapping() {
        /-* $keyMapping = $this->arrayKeyMap();
        $key = array_keys($keyMapping);
        $values = array_values($keyMapping); *-/
        return array(
            "device_type"   => "deviceType",
            "auth_type"     => "authType",
            "agent_type"    => "agentType",
            "user_email"    => "email",
            "password"      => "password",
        );
    }*/

    /*
     * Array key mapping
     * Array key mapping with request parameter and database column
     * Array left side is - request parameter
     * Array right side is - database column
     */
    public function arrayKeyMap() {
        $keyMapping = $this->propertyKeyMapping();
        $key = array_keys($keyMapping);
        //$values = array_values($keyMapping);
        /* return array(
            "deviceType"    => "device_type",
            "authType"      => "auth_type",
            "agentType"     => "agent_type",
            "email"         => "user_email",
            "password"      => "password",
        ); */

        return array(
            "deviceType" => $key[0],
            "authType" => $key[1],
            "agentType" => $key[2],
            "email" => $key[3],
            "password" => $key[4],
        );
    }

    /*
     * Array key mapping
     * Array key mapping with request parameter and database column
     * Array left side is - database column
     * Array right side is - request parameter
     */
    public function propertyKeyMapping() {
        /* $keyMapping = $this->arrayKeyMap();
        $key = array_keys($keyMapping);
        $values = array_values($keyMapping); */
        return array(
            "device_type" => "deviceType",
            "auth_type" => "authType",
            "agent_type" => "agentType",
            "user_email" => "email",
            "password" => "password",
        );
    }
}
?>