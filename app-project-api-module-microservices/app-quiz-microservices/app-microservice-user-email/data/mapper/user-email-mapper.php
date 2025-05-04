<?php
namespace App\Microservice\Data\Mapper\User\Email;
?>
<?php
use App\Microservice\Schema\Domain\Model\User\Email\UserEmailModel;
?>
<?php
class UserEmailMapper {
    public static function mapEntityToParams(UserEmailModel $email): array {
        return [
            ':user_id' => $email->user_id,
            ':id' => $email->id,
            ':email' => $email->email,
            ':provider' => $email->provider,
            ':is_primary' => $email->is_primary,
            ':verification_code' => $email->verification_code,
            ':last_verification_sent_at' => $email->last_verification_sent_at,
            ':verification_code_expiry' => $email->verification_code_expiry,
            ':verification_status' => $email->verification_status,
            ':status' => $email->status,
            ':created_date' => $email->created_date,
            ':modified_date' => $email->modified_date,
            ':created_by' => $email->created_by,
            ':modified_by' => $email->modified_by
        ];
    }
}