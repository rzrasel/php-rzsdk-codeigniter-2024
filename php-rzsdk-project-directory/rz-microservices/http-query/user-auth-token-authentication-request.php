<?php
namespace RzSDK\HTTPRequest;
?>
<?php
use RzSDK\Utils\ObjectPropertyWizard;
use RzSDK\Validation\PrepareValidationRules;
use RzSDK\Log\DebugLog;
?>
<?php
class UserAuthTokenAuthenticationRequest {
    public $device_type;
    public $auth_type;
    public $agent_type;
    public $user_auth_token;

    public function getQuery() {
        return ObjectPropertyWizard::getPublicVariableWithKeyValue($this);
    }

    public function device_type_rules() {
        $validationRules = new PrepareValidationRules();
        return array(
            $validationRules->notNullValidationRules("Device type can not be null"),
        );
    }

    public function auth_type_rules() {
        $validationRules = new PrepareValidationRules();
        return array(
            $validationRules->notNullValidationRules("Auth type can not be null"),
        );
    }

    public function agent_type_rules() {
        $validationRules = new PrepareValidationRules();
        return array(
            $validationRules->notNullValidationRules("Agent type can not be null"),
        );
    }

    public function user_auth_token_rules() {
        $validationRules = new PrepareValidationRules();
        return array(
            $validationRules->notNullValidationRules("User authentication token can not be null"),
        );
    }

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
        $tempDeviceType = $this->device_type;
        $tempAuthType = $this->auth_type;
        $tempAgentType = $this->agent_type;
        $tempUserAuthToken = $this->user_auth_token;
        $this->device_type = ObjectPropertyWizard::getVariableName();
        $this->auth_type = ObjectPropertyWizard::getVariableName();
        $this->agent_type = ObjectPropertyWizard::getVariableName();
        $this->user_auth_token = ObjectPropertyWizard::getVariableName();
        $dataList = array(
            $this->device_type      => "device_type",
            $this->auth_type        => "auth_type",
            $this->agent_type       => "agent_type",
            $this->user_auth_token  => "auth_token",
        );
        $this->device_type = $tempDeviceType;
        $this->auth_type = $tempAuthType;
        $this->agent_type = $tempAgentType;
        $this->user_auth_token = $tempUserAuthToken;
        return $dataList;
        /*return array(
            "device_type"       => "device_type",
            "auth_type"         => "auth_type",
            "agent_type"        => "agent_type",
            "user_auth_token"   => "email",
        );*/
    }
}
?>
