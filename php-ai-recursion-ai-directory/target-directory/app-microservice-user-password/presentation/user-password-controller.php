<?php
namespace App\Microservice\Presentation\Controller\Use\Password;
?>
<?php
use App\Microservice\Data\Repository\User\Password\UserPasswordRepositoryImpl;
use App\Microservice\Domain\UseCase\User\Password\UserPasswordUseCase;
use App\Microservice\Core\Utils\Data\Response\ResponseData;
use App\Microservice\Presentation\ViewModel\Use\Password\UserPasswordViewModel;
?>
<?php
class UserPasswordController {
    private UserPasswordViewModel $viewModel;

    public function __construct() {
        $repository = new UserPasswordRepositoryImpl();
        $useCase = new UserPasswordUseCase($repository);
        $this->viewModel = new UserPasswordViewModel($useCase);
    }

    public function createPassword(array $input): ResponseData {
        // Validate input
        try {
            $requiredFields = ['user_id', 'hash_type', 'password_hash', 'modified_by', 'created_by'];
            foreach ($requiredFields as $field) {
                if (empty($input[$field])) {
                    return new ResponseData(false, "Missing required field: $field", null, 400);
                }
            }

            return $this->viewModel->createPassword($input);
        } catch (\Exception $e) {
            return new ResponseData(false, 'Failed to create password: ' . $e->getMessage(), null, 500);
        }
    }

    public function getById(string $id): ResponseData {
        try {
            return $this->viewModel->getPasswordById($id);
        } catch (\Exception $e) {
            return new ResponseData(false, 'Failed to create password: ' . $e->getMessage(), null, 500);
        }
    }

    public function getByUserId(string $userId): ResponseData {
        try {
            return $this->viewModel->getPasswordByUserId($userId);
        } catch (\Exception $e) {
            return new ResponseData(false, 'Failed to create password: ' . $e->getMessage(), null, 500);
        }
    }

    public function update(array $input): ResponseData {
        // Validate input
        try {
            $requiredFields = ['id', 'hash_type', 'password_hash', 'modified_by'];
            foreach ($requiredFields as $field) {
                if (empty($input[$field])) {
                    return new ResponseData(false, "Missing required field: $field", null, 400);
                }
            }

            return $this->viewModel->updatePassword($input);
        } catch (\Exception $e) {
            return new ResponseData(false, 'Failed to create password: ' . $e->getMessage(), null, 500);
        }
    }
}
?>
