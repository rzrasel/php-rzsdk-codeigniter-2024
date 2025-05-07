<?php
namespace App\Microservice\Schema\Data\Model\User\Password;
?>
<?php
class UserPasswordUpdateRequestModel {
    public ?string $user_id;
    public ?string $id;
    public ?string $password;
    public mixed $action_type;

    public function __construct(
        ?string $user_id = null,
        ?string $id = null,
        ?string $password = null,
        mixed $action_type = null
    ) {
        $this->user_id = $user_id;
        $this->id = $id;
        $this->password = $password;
        $this->action_type = $action_type;
    }

    public function mapToDataModel() {}

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