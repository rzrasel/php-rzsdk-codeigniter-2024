<?php
namespace App\Microservice\Schema\Data\Model\User\Password;
?>
<?php
class UserPasswordRequestModel {
    public $user_id;
    public $password;
    public $action_type;

    public function __construct(
        $user_id = null,
        $password = null,
        $action_type = null
    ) {
        $this->user_id = $user_id;
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