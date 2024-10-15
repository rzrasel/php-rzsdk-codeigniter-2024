<?php
namespace RzSDK\Model\User\Authentication\Token;
?>
<?php
use RzSDK\Utils\ObjectPropertyWizard;
use RzSDK\Log\DebugLog;
?>
<?php
class UserAuthTokenAuthenticationResponseModel {
    /*public $device_type;
    public $auth_type;
    public $agent_type;*/
    public $user_id;
    //public $user_email;
    public $user_auth_token;

    public function getQuery() {
        return ObjectPropertyWizard::getPublicVariableWithKeyValue($this);
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
}
?>