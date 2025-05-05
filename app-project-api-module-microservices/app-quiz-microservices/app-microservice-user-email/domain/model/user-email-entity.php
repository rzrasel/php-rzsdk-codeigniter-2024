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
        $id = null,
        $email = null,
        $provider = null,
        $is_primary = null,
        $verification_code = null,
        $last_verification_sent_at = null,
        $verification_code_expiry = null,
        $verification_status = null,
        $status = null,
        $created_date = null,
        $modified_date = null,
        $created_by = null,
        $modified_by = null
    ) {
        $this->user_id = $user_id;
        $this->id = $id;
        $this->email = $email;
        $this->provider = $provider;
        $this->is_primary = $is_primary;
        $this->verification_code = $verification_code;
        $this->last_verification_sent_at = $last_verification_sent_at;
        $this->verification_code_expiry = $verification_code_expiry;
        $this->verification_status = $verification_status;
        $this->status = $status;
        $this->created_date = $created_date;
        $this->modified_date = $modified_date;
        $this->created_by = $created_by;
        $this->modified_by = $modified_by;
    }

    public static function mapToEntityColumn() {
        return new self(
            "user_id",
            "id",
            "email",
            "provider",
            "is_primary",
            "verification_code",
            "last_verification_sent_at",
            "verification_code_expiry",
            "verification_status",
            "status",
            "created_date",
            "modified_date",
            "created_by",
            "modified_by"
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
?>
