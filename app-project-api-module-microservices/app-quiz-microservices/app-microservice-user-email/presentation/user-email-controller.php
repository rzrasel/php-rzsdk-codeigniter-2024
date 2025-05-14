<?php
namespace App\Microservice\Presentation\Controller\Use\Email;
?>
<?php
use App\Microservice\Core\Utils\Data\Response\ResponseData;
use App\Microservice\Core\Utils\Type\Response\ResponseStatus;
use App\Microservice\Dependency\Injection\Module\Use\Email\UserEmailModule;
use App\Microservice\Presentation\ViewModel\Use\Email\UserEmailViewModel;
?>
<?php
class UserEmailController {
    private UserEmailViewModel $viewModel;
    public function __construct() {
        $this->viewModel = (new UserEmailModule())->provideViewModel();
    }

    public function executeController(array $request): ResponseData {
        try {
            return $this->viewModel->executeViewModel($request);
        } catch (\Throwable $e) {
            return new ResponseData(
                "An error occurred: " . $e->getMessage(),
                ResponseStatus::ERROR,
                $request,
                500
            );
        }
    }
}
?>