<?php
namespace App\Microservice\Schema\Data\Model\User\Password;
?>
<?php
class UserPasswordSelectRequestModel {
    public ?string $user_id;
    public ?string $id;
    public ?string $expiry;
    public mixed $status;
    public mixed $columns;
    public mixed $action_type;

    public function __construct(
        ?string $user_id = null,
        ?string $id = null,
        ?string $expiry = null,
        ?string $status = null,
        mixed $action_type = null,
        mixed $columns = array()
    ) {
        $this->user_id = $user_id;
        $this->id = $id;
        $this->expiry = $expiry;
        $this->status = $status;
        $this->action_type = $action_type;
        $this->columns = $columns;
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