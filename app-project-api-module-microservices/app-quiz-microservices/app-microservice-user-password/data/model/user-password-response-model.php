<?php
namespace App\Microservice\Schema\Data\Model\User\Password;
?>
<?php
class UserPasswordResponseModel {
    public ?string $user_id;
    public ?string $id;
    public mixed $hash_type;
    public ?string $password_salt;
    public ?string $password_hash;
    public ?string $expiry;
    public mixed $status;

    public function __construct(
        string $user_id = null,
        string $id = null,
        mixed $hash_type = null,
        string $password_salt = null,
        string $password_hash = null,
        string $expiry = null,
        mixed $status = null,
    ) {
        $this->user_id = $user_id;
        $this->id = $id;
        $this->hash_type = $hash_type;
        $this->password_salt = $password_salt;
        $this->password_hash = $password_hash;
        $this->expiry = $expiry;
        $this->status = $status;
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