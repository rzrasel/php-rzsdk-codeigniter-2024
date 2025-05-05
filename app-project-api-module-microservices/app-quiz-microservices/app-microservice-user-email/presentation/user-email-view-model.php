<?php
namespace App\Microservice\Presentation\Controller\Use\Email;
?>
<?php
use App\Microservice\Core\Utils\Data\Response\ResponseData;
use App\Microservice\Schema\Data\Model\User\Email\UserEmailRequestModel;
use App\Microservice\Schema\Data\Services\User\Email\UserEmailService;
use App\Microservice\Action\Type\Email\EmailActionType;
use App\Microservice\Core\Utils\Type\Response\ResponseStatus;
use RzSDK\Identification\UniqueIntId;
?>
<?php
class UserEmailViewModel {
    private $service;

    public function __construct(UserEmailService $service) {
        $this->service = $service;
    }

    public function userEmailAction($requestDataSet): ResponseData {
        $userEmailRequestModel = new UserEmailRequestModel();
        $varList = $userEmailRequestModel->getVarList();
        foreach ($varList as $key) {
            if (array_key_exists($key, $requestDataSet)) {
                $userEmailRequestModel->{$key} = $requestDataSet[$key];
            }
        }
        $mappedKey = UserEmailRequestModel::mapKeyToUserInput();
        foreach ($mappedKey as $key => $value) {
            if (array_key_exists($value, $requestDataSet)) {
                $userEmailRequestModel->{$key} = $requestDataSet[$value];
            }
        }
        return $this->executeEmailAction($userEmailRequestModel);
    }

    public function executeEmailAction(UserEmailRequestModel $userEmailRequestModel): ResponseData {
        if(empty($userEmailRequestModel->action_type)) {
            return new ResponseData("Failed to create user email action type is null", ResponseStatus::ERROR, $userEmailRequestModel);
        }
        $actionType = EmailActionType::getByValue($userEmailRequestModel->action_type);
        if ($actionType == EmailActionType::CREATE) {
            $uniqueIntId = new UniqueIntId();
            $userEmailRequestModel->id = $uniqueIntId->getId();
            return $this->service->addEmail($userEmailRequestModel);
        } else {
            return new ResponseData("Failed to create user email action type not found", ResponseStatus::ERROR, $userEmailRequestModel);
        }
    }
}
?>