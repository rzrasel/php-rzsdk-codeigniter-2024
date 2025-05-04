<?php
namespace App\Microservice\Schema\Domain\Model\User\Email;
?>
<?php
class UserEmailEntity {
    public $user_id;
    public $id;
    public $email;
    public $provider;
    public $is_primary;
    public $verification_code;
    public $last_verification_sent_at;
    public $verification_code_expiry;
    public $verification_status;
    public $status;
    public $created_date;
    public $modified_date;
    public $created_by;
    public $modified_by;

    public function __construct(
        $user_id = null,
        $email = null,
        $created_by = null,
        $modified_by = null,
        $provider = null,
        $is_primary = null,
        $verification_status = null,
        $status = null,
        $id = null
    ) {
        $this->id = $id;
        $this->user_id = $user_id;
        $this->email = $email;
        $this->provider = $provider;
        $this->is_primary = $is_primary;
        $this->verification_status = $verification_status;
        $this->status = $status;
        $this->created_by = $created_by;
        $this->modified_by = $modified_by;
        $this->created_date = date('Y-m-d H:i:s');
        $this->modified_date = date('Y-m-d H:i:s');
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
