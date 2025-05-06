<?php
namespace App\Microservice\Presentation\ViewModel\Use\Password;
?>
<?php
use App\Microservice\Core\Utils\Data\Response\ResponseData;
use App\Microservice\Domain\UseCase\User\Password\UserPasswordUseCase;
use App\Microservice\Schema\Domain\Model\User\Password\UserPasswordEntity;
?>
<?php
class UserPasswordViewModel {

    private UserPasswordUseCase $useCase;

    public function __construct(UserPasswordUseCase $useCase) {
        $this->useCase = $useCase;
    }

    public static function createResponse(ResponseData $response): string {
        http_response_code($response->statusCode);
        header('Content-Type: application/json');
        return $response->toJson();
    }

    public static function formatPasswordData(array $passwordData): array {
        return [
            'user_id' => $passwordData['user_id'],
            'id' => $passwordData['id'],
            'hash_type' => $passwordData['hash_type'],
            'status' => $passwordData['status'],
            'expiry' => $passwordData['expiry'],
            'created_date' => $passwordData['created_date'],
            'modified_date' => $passwordData['modified_date']
        ];
    }

    public function createPassword(array $input): ResponseData {
        $userPassword = new UserPasswordEntity();
        $userPassword->user_id = $input['user_id'];
        $userPassword->id = $input['id'] ?? uniqid();
        $userPassword->hash_type = $input['hash_type'];
        $userPassword->password_salt = $input['password_salt'] ?? null;
        $userPassword->password_hash = $input['password_hash'];
        $userPassword->expiry = $input['expiry'] ?? null;
        $userPassword->status = $input['status'] ?? 'active';
        $userPassword->modified_by = $input['modified_by'];
        $userPassword->created_by = $input['created_by'];
        return $this->useCase->createPassword($userPassword);
    }

    public function getById(string $id): ResponseData {
        return $this->useCase->getPasswordById($id);
    }

    public function getByUserId(string $userId): ResponseData {
        return $this->useCase->getPasswordByUserId($userId);
    }

    public function update(array $input): ResponseData {
        $userPassword = new UserPasswordEntity();
        $userPassword->id = $input['id'];
        $userPassword->hash_type = $input['hash_type'];
        $userPassword->password_salt = $input['password_salt'] ?? null;
        $userPassword->password_hash = $input['password_hash'];
        $userPassword->expiry = $input['expiry'] ?? null;
        $userPassword->status = $input['status'] ?? 'active';
        $userPassword->modified_by = $input['modified_by'];

        return $this->useCase->updatePassword($userPassword);
    }
}
?>