<?php
namespace RzSDK\Model\User\Authentication;
?>
<?php
use RzSDK\Utils\ObjectPropertyWizard;
use RzSDK\HTTPRequest\UserAuthTokenAuthenticationRequest;
use RzSDK\Log\DebugLog;
?>
<?php
class UserAuthTokenAuthenticationRequestModel {
    public UserAuthTokenAuthenticationRequest $objectUserAuthTokenAuthRequest;

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
