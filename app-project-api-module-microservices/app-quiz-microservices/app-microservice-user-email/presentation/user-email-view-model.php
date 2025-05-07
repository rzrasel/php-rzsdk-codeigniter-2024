<?php
namespace App\Microservice\Presentation\ViewModel\Use\Email;
?>
<?php
use App\Microservice\Core\Utils\Data\Response\ResponseData;
use App\Microservice\Schema\Data\Model\User\Email\UserEmailInsertRequestModel;
use App\Microservice\Domain\UseCase\User\Email\UserEmailUseCase;
use App\Microservice\Type\Action\Email\EmailActionType;
use App\Microservice\Core\Utils\Type\Response\ResponseStatus;
use App\Microservice\Schema\Data\Model\User\Email\UserEmailSelectRequestModel;
?>
<?php
class UserEmailViewModel {
    private $useCase;

    public function __construct(UserEmailUseCase $useCase) {
        $this->useCase = $useCase;
    }

    public function executeViewModel(array $requestDataSet): ResponseData {
        $actionType = EmailActionType::getByValue($requestDataSet["action_type"]);
        if ($actionType == EmailActionType::INSERT) {
            return $this->createEmail($requestDataSet);
        } else if ($actionType == EmailActionType::SELECT) {
            return $this->selectEmail($requestDataSet);
        }
        return new ResponseData("Missing requested action type", ResponseStatus::ERROR, $requestDataSet, 404);
    }

    public function createEmail(array $requestDataSet): ResponseData {
        $requiredFields = array(
            "user_id",
            "action_type",
        );
        foreach ($requiredFields as $field) {
            if (empty($requestDataSet[$field])) {
                return new ResponseData("Missing required field: $field", ResponseStatus::ERROR, 400);
            }
        }
        //
        $userEmailRequestModel = new UserEmailInsertRequestModel();
        $varList = $userEmailRequestModel->getVarList();
        foreach ($varList as $key) {
            if (array_key_exists($key, $requestDataSet)) {
                $userEmailRequestModel->{$key} = $requestDataSet[$key];
            }
        }
        //
        //return new ResponseData("User email created successfully.", ResponseStatus::SUCCESS, $userEmailModel, 201);
        return $this->useCase->createEmail($userEmailRequestModel);
    }

    public function selectEmail(array $requestDataSet): ResponseData {
        $requiredFields = array(
            "action_type",
        );
        foreach ($requiredFields as $field) {
            if (empty($requestDataSet[$field])) {
                return new ResponseData("Missing required field: $field", ResponseStatus::ERROR, 400);
            }
        }
        $userEmail = new UserEmailSelectRequestModel();
        //
        $dataVarList = $userEmail->getVarList();
        foreach ($dataVarList as $key) {
            if(array_key_exists($key, $requestDataSet)) {
                $userEmail->{$key} = $requestDataSet[$key];
            }
        }
        //
        $columnRawData = $userEmail->columns;
        if (!empty($columnRawData)) {
            if (is_array($columnRawData)) {
                $userEmail->columns = $columnRawData;
            } else if (is_string($columnRawData)) {
                $decoded = json_decode($columnRawData, true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                    $userEmail->columns = $decoded;
                } else {
                    // Treat as comma-separated string
                    $userEmail->columns = array_map('trim', explode(',', $columnRawData));
                }
            }
        }
        //
        //return new ResponseData("User email selected successfully.", ResponseStatus::SUCCESS, $requestDataSet, 200);
        return $this->useCase->selectEmail($userEmail);
    }
}
?>