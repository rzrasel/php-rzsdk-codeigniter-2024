<?php
namespace App\Microservice\Presentation\ViewModel\Use\Password;
?>
<?php
use App\Microservice\Core\Utils\Data\Response\ResponseData;
use App\Microservice\Domain\UseCase\User\Password\UserPasswordUseCase;
use App\Microservice\Schema\Domain\Model\User\Password\UserPasswordEntity;
use App\Microservice\Schema\Data\Model\User\Password\UserPasswordModel;
use App\Microservice\Schema\Data\Model\User\Password\UserPasswordRequestModel;
use App\Microservice\Type\Action\Password\PasswordActionType;
use App\Microservice\Core\Utils\Type\Response\ResponseStatus;
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

    public function createPassword(array $requestDataSet): ResponseData {
        $userPassword = new UserPasswordRequestModel();
        $dataVarList = $userPassword->getVarList();
        foreach ($dataVarList as $key) {
            $userPassword->{$key} = $requestDataSet[$key];
        }
        $actionType = PasswordActionType::getByValue($userPassword->action_type);
        if ($actionType == PasswordActionType::CREATE) {
            return $this->useCase->createPassword($userPassword);
        }
        return new ResponseData("Missing requested action type", ResponseStatus::ERROR, 400);
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