<?php
namespace RzSDK\Model\User\Authentication;
?>
<?php
use RzSDK\Utils\ObjectPropertyWizard;
use RzSDK\Log\DebugLog;
?>
<?php
class UserAuthTokenAuthenticationRequestModel {
    public $device_type;
    public $auth_type;
    public $agent_type;
    public $user_email;
    public $password;
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
}
?>
