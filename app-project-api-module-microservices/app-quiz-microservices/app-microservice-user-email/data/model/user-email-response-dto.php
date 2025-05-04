<?php
namespace App\Microservice\Schema\Data\Model\User\Email;
?>
<?php
class UserEmailResponseDto {
    public $user_id;
    public $id;
    public $user_email;
    public $email_provider;
    public $is_primary;
    public $last_verification_sent_at;
    public $verification_code_expiry;
    public $verification_status;
    public $status;

    public function __construct(
        int $user_id = null,
        string $id = null,
        string $user_email = null,
        string $email_provider = null,
        bool $is_primary = null,
        string $last_verification_sent_at = null,
        string $verification_code_expiry = null,
        string $verification_status = null,
        string $status = null,
    ) {
        $this->user_id = $user_id;
        $this->id = $id;
        $this->user_email = $user_email;
        $this->email_provider = $email_provider;
        $this->is_primary = $is_primary;
        $this->last_verification_sent_at = $last_verification_sent_at;
        $this->verification_code_expiry = $verification_code_expiry;
        $this->verification_status = $verification_status;
        $this->status = $status;
    }

    public function toArray(): array {
        return [
            'id' => $this->id,
            'email' => $this->email,
            'is_primary' => $this->is_primary,
            'verification_status' => $this->verification_status,
            'status' => $this->status,
            'created_date' => $this->created_date
        ];
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