<?php
namespace App\Microservice\Presentation\Controller\Use\Email;
?>
<?php
use App\Microservice\Core\Utils\Data\Response\ResponseData;
use App\Microservice\Schema\Data\Model\User\Email\UserEmailRequestModel;
use App\Microservice\Schema\Data\Services\User\Email\UserEmailService;
use App\Microservice\Action\Type\Email\EmailActionType;
use App\Microservice\Core\Utils\Type\Response\ResponseStatus;
?>
<?php
class UserEmailViewModel {
    private $service;

    public function __construct(UserEmailService $service) {
        $this->service = $service;
    }

    public function userEmailAction($requestDataSet): ResponseData {
        $userEmailRequestModel = new UserEmailRequestModel();
        $userEmailRequestModel->user_id = $requestDataSet["user_id"];
        $userEmailRequestModel->email = $requestDataSet["user_email"];
        $userEmailRequestModel->provider = $requestDataSet["email_provider"];
        $userEmailRequestModel->is_primary = $requestDataSet["is_primary"];
        $userEmailRequestModel->verification_code = $requestDataSet["verification_code"];
        $userEmailRequestModel->action_type = $requestDataSet["action_type"];
        $actionType = EmailActionType::getByValue($userEmailRequestModel->action_type);
        if ($actionType == EmailActionType::CREATE) {
            return $this->service->addEmail($userEmailRequestModel);
        } else {
            return new ResponseData("Failed to create user email action type not found", ResponseStatus::ERROR, $requestDataSet);
        }
    }
}
?>