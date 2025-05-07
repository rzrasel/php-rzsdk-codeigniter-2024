<?php
namespace App\Microservice\Presentation\Controller\Use\Email;
?>
<?php
use App\Microservice\Core\Utils\Data\Response\ResponseData;
use App\Microservice\Core\Utils\Type\Response\ResponseStatus;
use App\Microservice\Domain\UseCase\User\Email\UserEmailUseCase;
use App\Microservice\Data\Repository\User\Email\UserEmailRepositoryImpl;
use App\Microservice\Dependency\Injection\Module\Use\Email\UserEmailModule;
use App\Microservice\Presentation\ViewModel\Use\Email\UserEmailViewModel;
?>
<?php
class UserEmailController {
    private UserEmailViewModel $viewModel;
    public function __construct() {
        $this->viewModel = (new UserEmailModule())->provideViewModel();
    }

    public function executeController(array $requestDataSet): ResponseData {
        try {
            if (empty($requestDataSet["action_type"])) {
                return new ResponseData("Missing required field: action_type", ResponseStatus::ERROR, 404);
            }
            return $this->viewModel->executeViewModel($requestDataSet);
        } catch (\Exception $e) {
            return new ResponseData("Failed to create user email: " . $e->getMessage(), ResponseStatus::ERROR);
        }
    }
}
?>