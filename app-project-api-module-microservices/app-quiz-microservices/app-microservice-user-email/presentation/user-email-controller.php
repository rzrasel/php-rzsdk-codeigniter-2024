<?php
namespace App\Microservice\Presentation\Controller\Use\Email;
?>
<?php
use App\Microservice\Core\Utils\Data\Response\ResponseData;
use App\Microservice\Core\Utils\Type\Response\ResponseStatus;
use App\Microservice\Schema\Data\Services\User\Email\UserEmailService;
use App\Microservice\Data\Repository\User\Email\UserEmailRepositoryImpl;

?>
<?php
class UserEmailController {
    private UserEmailService $service;
    private UserEmailViewModel $viewModel;
    public function __construct() {
        $repository = new UserEmailRepositoryImpl();
        $service = new UserEmailService($repository);
        $this->viewModel = new UserEmailViewModel($service);
    }

    public function addEmail($requestDataSet): ResponseData {
        if(empty($requestDataSet)) {
            return new ResponseData("Failed data empty", ResponseStatus::ERROR);
        }
        try {
            $response = $this->viewModel->userEmailAction($requestDataSet);
            //return new ResponseData("User email created successfully.", ResponseStatus::SUCCESS, $requestDataSet);
            return $response;
        } catch (\Exception $e) {
            return new ResponseData("Failed to create user email: " . $e->getMessage(), ResponseStatus::ERROR);
        }
    }
}
?>