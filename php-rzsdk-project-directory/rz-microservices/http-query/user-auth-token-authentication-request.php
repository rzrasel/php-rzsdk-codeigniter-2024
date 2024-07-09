<?php
namespace RzSDK\HTTPRequest;
?>
<?php
use RzSDK\Utils\ObjectPropertyWizard;
use RzSDK\Log\DebugLog;
?>
<?php
class UserAuthTokenAuthenticationRequest {
    public $device_type;
    public $auth_type;
    public $agent_type;
    public $user_auth_token;

    public function getPropertyKeyValue() {
        return ObjectPropertyWizard::getPublicVariableWithKeyValue($this);
    }

    public function getPropertyKeys() {
        return ObjectPropertyWizard::getPublicVariableKeys($this);
    }

    public function getPropertyValues() {
        return ObjectPropertyWizard::getPublicVariableValues($this);
    }

    public function toTypeCasting($object): self {
        return $object;
    }

    /*
     * Array key mapping
     * Array key mapping with request parameter and database column
     * Array left side is - request parameter
     * Array right side is - database column
     */
    public function keyMapping() {
        $this->device_type = ObjectPropertyWizard::getVariableName();
        $this->auth_type = ObjectPropertyWizard::getVariableName();
        $this->agent_type = ObjectPropertyWizard::getVariableName();
        $this->user_auth_token = ObjectPropertyWizard::getVariableName();
        return array(
            $this->device_type      => "device_type",
            $this->auth_type        => "auth_type",
            $this->agent_type       => "agent_type",
            $this->user_auth_token  => "email",
        );
        /*return array(
            "device_type"       => "device_type",
            "auth_type"         => "auth_type",
            "agent_type"        => "agent_type",
            "user_auth_token"   => "email",
        );*/
    }
}
?>
