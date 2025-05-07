<?php
namespace App\Microservice\Schema\Domain\Model\User\Password;
?>
<?php
class UserPasswordEntity {
    public ?string $user_id;
    public ?string $id;
    public mixed $hash_type;
    public ?string $password_salt;
    public ?string $password_hash;
    public ?string $expiry;
    public mixed $status;
    public ?string $modified_date;
    public ?string $created_date;
    public ?string $modified_by;
    public ?string $created_by;

    public function __construct(
        string $user_id = null,
        string $id = null,
        mixed $hash_type = null,
        string $password_salt = null,
        string $password_hash = null,
        string $expiry = null,
        mixed $status = null,
        string $modified_date = null,
        string $created_date = null,
        string $modified_by = null,
        string $created_by = null,
    ) {
        $this->user_id = $user_id;
        $this->id = $id;
        $this->hash_type = $hash_type;
        $this->password_salt = $password_salt;
        $this->password_hash = $password_hash;
        $this->expiry = $expiry;
        $this->status = $status;
        $this->modified_date = $modified_date;
        $this->created_date = $created_date;
        $this->modified_by = $modified_by;
        $this->created_by = $created_by;
    }

    public static function mapToEntityColumn() {
        return new self(
            "user_id",
            "id",
            "hash_type",
            "password_salt",
            "password_hash",
            "expiry",
            "status",
            "modified_date",
            "created_date",
            "modified_by",
            "created_by"
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