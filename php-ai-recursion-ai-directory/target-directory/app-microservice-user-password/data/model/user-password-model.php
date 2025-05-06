<?php
namespace App\Microservice\Schema\Data\Model\User\Password;
?>
<?php
class UserPasswordModel {
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
}
?>