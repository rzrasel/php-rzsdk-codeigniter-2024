<?php
namespace App\Microservice\Schema\Data\Model\User\Email;
?>
<?php
class UserEmailRequestModel {
    public $user_id;
    public $id;
    public $email;
    public $provider;
    public $is_primary;
    public $verification_code;
    public $action_type;

    public function __construct(
        $user_id = null,
        $id = null,
        $email = null,
        $provider = null,
        $is_primary = null,
        $verification_code = null,
        $action_type = null,
    ) {
        $this->user_id = $user_id;
        $this->id = $id;
        $this->email = $email;
        $this->provider = $provider;
        $this->is_primary = $is_primary;
        $this->verification_code = $verification_code;
        $this->action_type = $action_type;
    }

    public function mapToDataModel() {}

    public static function mapKeyToUserInput() {
        return array(
            "email" => "user_email",
            "provider" => "email_provider",
        );
    }

    public function getVarList() {
        $result = array_intersect_key(
            get_object_vars($this),
            get_mangled_object_vars($this)
        );
        return array_keys($result);
    }

    public function getVarListWithKey() {
        return array_intersect_key(
            get_object_vars($this),
            get_mangled_object_vars($this)
        );
    }
}