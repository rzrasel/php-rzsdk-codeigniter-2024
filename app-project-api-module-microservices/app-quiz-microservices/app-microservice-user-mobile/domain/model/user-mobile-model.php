<?php
namespace App\Microservice\Schema\Domain\Model\User\Model;
?>
<?php
class UserMobileModel {
    public string $user_id;
    public string $id;
    public string $mobile;
    public ?string $country_code;
    public string $provider;
    public bool $is_primary;
    public ?string $verification_code;
    public ?string $last_verification_sent_at;
    public ?string $verification_code_expiry;
    public string $verification_status;
    public string $status;
    public string $modified_date;
    public string $created_date;
    public string $modified_by;
    public string $created_by;

    public function __construct(
        string $user_id,
        string $id,
        string $mobile,
        ?string $country_code,
        string $provider,
        bool $is_primary,
        ?string $verification_code,
        ?string $last_verification_sent_at,
        ?string $verification_code_expiry,
        string $verification_status,
        string $status,
        string $modified_date,
        string $created_date,
        string $modified_by,
        string $created_by
    ) {
        $this->user_id = $user_id;
        $this->id = $id;
        $this->mobile = $mobile;
        $this->country_code = $country_code;
        $this->provider = $provider;
        $this->is_primary = $is_primary;
        $this->verification_code = $verification_code;
        $this->last_verification_sent_at = $last_verification_sent_at;
        $this->verification_code_expiry = $verification_code_expiry;
        $this->verification_status = $verification_status;
        $this->status = $status;
        $this->modified_date = $modified_date;
        $this->created_date = $created_date;
        $this->modified_by = $modified_by;
        $this->created_by = $created_by;
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
