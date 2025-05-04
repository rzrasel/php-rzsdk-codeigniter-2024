<?php
namespace App\Microservice\Schema\Domain\Model\User\Email;
?>
<?php
class UserEmailModel {
    public string $id;
    public string $user_id;
    public string $email;
    public string $provider;
    public bool $is_primary;
    public ?string $verification_code;
    public ?string $last_verification_sent_at;
    public ?string $verification_code_expiry;
    public string $verification_status;
    public string $status;
    public string $created_date;
    public string $modified_date;
    public string $created_by;
    public string $modified_by;

    public function __construct(
        string $user_id,
        string $email,
        string $created_by,
        string $modified_by,
        string $provider = 'user',
        bool $is_primary = false,
        string $verification_status = 'pending',
        string $status = 'active',
        string $id = null
    ) {
        $this->id = $id ?? uuid_create();
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
}
?>
