<?php
namespace RzSDK\Model\User\UserInfo;
?>
<?php
use RzSDK\Utils\ObjectPropertyWizard;
use RzSDK\Log\DebugLog;
?>
<?php
class UserInfoResponseModel {
    public $user_id  = "user_id";
    public $user_email  = "user_email";

    public function toParameterKeyValue() {
        return ObjectPropertyWizard::getPublicVariableWithKeyValue($this);
    }
}
?>