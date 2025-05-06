<?php
namespace App\Microservice\Schema\Domain\Model\User\Password;
?>
<?php
class UserPasswordEntity {
    public string $user_id;
    public string $id;
    public string $hash_type;
    public ?string $password_salt;
    public string $password_hash;
    public ?string $expiry;
    public string $status;
    public string $modified_date;
    public string $created_date;
    public string $modified_by;
    public string $created_by;

    public function toArray(): array {
        return [
            'user_id' => $this->user_id,
            'id' => $this->id,
            'hash_type' => $this->hash_type,
            'password_salt' => $this->password_salt,
            'password_hash' => $this->password_hash,
            'expiry' => $this->expiry,
            'status' => $this->status,
            'modified_date' => $this->modified_date,
            'created_date' => $this->created_date,
            'modified_by' => $this->modified_by,
            'created_by' => $this->created_by
        ];
    }
}
?>