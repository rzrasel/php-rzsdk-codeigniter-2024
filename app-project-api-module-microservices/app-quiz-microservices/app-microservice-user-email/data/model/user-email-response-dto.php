<?php
namespace App\Microservice\Schema\Data\Model\User\Email;
?>
<?php
class UserEmailResponseDto {
    public string $id;
    public string $email;
    public bool $is_primary;
    public string $verification_status;
    public string $status;
    public string $created_date;

    public function __construct(
        string $id,
        string $email,
        bool $is_primary,
        string $verification_status,
        string $status,
        string $created_date
    ) {
        $this->id = $id;
        $this->email = $email;
        $this->is_primary = $is_primary;
        $this->verification_status = $verification_status;
        $this->status = $status;
        $this->created_date = $created_date;
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
}