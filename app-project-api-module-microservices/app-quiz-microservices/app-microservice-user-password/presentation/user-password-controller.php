<?php
namespace App\Microservice\Presentation\Controller\Use\Password;
?>
<?php
use App\Microservice\Core\Utils\Data\Response\ResponseData;
use App\Microservice\Presentation\ViewModel\Use\Password\UserPasswordViewModel;
use App\Microservice\Core\Utils\Type\Response\ResponseStatus;
use App\Microservice\Dependency\Injection\Module\Use\Password\UserPasswordModule;
?>
<?php
class UserPasswordController {
    private UserPasswordViewModel $viewModel;

    public function __construct() {
        $userPasswordModule = new UserPasswordModule();
        $this->viewModel = $userPasswordModule->provideViewModel();
    }

    public function createPassword(array $requestDataSet): ResponseData {
        // Validate input
        try {
            $requiredFields = array(
                "user_id",
                "password",
                "action_type",
            );
            foreach ($requiredFields as $field) {
                if (empty($requestDataSet[$field])) {
                    return new ResponseData("Missing required field: $field", ResponseStatus::ERROR, 400);
                }
            }

            return $this->viewModel->createPassword($requestDataSet);
        } catch (\Exception $e) {
            return new ResponseData("Failed to create password: " . $e->getMessage(), ResponseStatus::ERROR, 500);
        }
    }

    /*public function getById(string $id): ResponseData {
        try {
            return $this->viewModel->getPasswordById($id);
        } catch (\Exception $e) {
            return new ResponseData("Failed to create password: " . $e->getMessage(), ResponseStatus::ERROR, 500);
        }
    }

    public function getByUserId(string $userId): ResponseData {
        try {
            return $this->viewModel->getPasswordByUserId($userId);
        } catch (\Exception $e) {
            return new ResponseData("Failed to create password: " . $e->getMessage(), ResponseStatus::ERROR, 500);
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
            return new ResponseData("Failed to create password: " . $e->getMessage(), ResponseStatus::ERROR, 500);
        }
    }*/
}
?>
