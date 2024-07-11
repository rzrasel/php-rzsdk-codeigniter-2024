<?php
namespace RzSDK\Model\User\Registration;
?>
<?php
use RzSDK\Utils\ObjectPropertyWizard;
use RzSDK\Log\DebugLog;
?>
<?php
class UserRegistrationResponseModel {
    public $user_id  = "user_id";
    public $user_email  = "user_email";
    public $user_auth_token;

    public function toParameterKeyValue() {
        return ObjectPropertyWizard::getPublicVariableWithKeyValue($this);
    }
}
?>