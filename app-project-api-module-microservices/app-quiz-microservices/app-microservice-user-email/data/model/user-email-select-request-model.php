<?php
namespace App\Microservice\Schema\Data\Model\User\Email;
?>
<?php
class UserEmailSelectRequestModel {
    public $user_id;
    public $user_email;
    public $email_provider;
    public $is_primary;
    public $verification_code;
    public $last_verification_sent_at;
    public $verification_code_expiry;
    public $verification_status;
    public $status;
    public mixed $columns;
    public $action_type;

    public function __construct(
        $user_id = null,
        $user_email = null,
        $email_provider = null,
        $is_primary = null,
        $verification_code = null,
        $columns = null,
        $action_type = null,
    ) {
        $this->user_id = $user_id;
        $this->user_email = $user_email;
        $this->email_provider = $email_provider;
        $this->is_primary = $is_primary;
        $this->verification_code = $verification_code;
        $this->columns = $columns;
        $this->action_type = $action_type;
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
?>