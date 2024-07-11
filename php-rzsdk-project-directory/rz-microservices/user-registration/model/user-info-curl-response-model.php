<?php
namespace RzSDK\Model\User\Registration;
?>
<?php
use RzSDK\DatabaseSpace\UserInfoTable;
use RzSDK\Utils\ObjectPropertyWizard;
use RzSDK\Response\InfoType;
use RzSDK\Log\DebugLog;
?>
<?php
class UserInfoCurlResponseModel {
    public $user_id = "user_id";
    public $email = "email";
    public $status = "status";
    public $modified_by = "modified_by";
    public $created_by = "created_by";
    public $modified_date = "modified_date";
    public $created_date = "created_date";
    public InfoType $infoType;
    public $responseBody;

    public function toParameterKeyValue() {
        return ObjectPropertyWizard::getPublicVariableWithKeyValue($this);
    }
}
?>