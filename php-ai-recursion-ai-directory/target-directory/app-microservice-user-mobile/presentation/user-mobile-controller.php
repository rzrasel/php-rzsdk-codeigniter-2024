<?php
namespace App\Microservice\Presentation\Controller\Use\Mobile;
?>
<?php
use App\Microservice\Core\Utils\Data\Response\ResponseData;
use App\Microservice\Core\Utils\Type\Response\ResponseStatus;
use App\Microservice\Dependency\Injection\Module\Use\Mobile\UserMobileModule;
use App\Microservice\Presentation\ViewModel\Use\Mobile\UserMobileViewModel;
?>
<?php
class UserMobileController {
    private UserMobileViewModel $viewModel;
    public function __construct() {
        $this->viewModel = (new UserMobileModule())->provideViewModel();
    }

    public function executeController(array $request): ResponseData {
        try {
            return $this->viewModel->executeViewModel($request);
        } catch (\Throwable $e) {
            return new ResponseData(
                "An error occurred: " . $e->getMessage(),
                ResponseStatus::ERROR,
                $request,
                500,
                __LINE__
            );
        }
    }
}
?>
