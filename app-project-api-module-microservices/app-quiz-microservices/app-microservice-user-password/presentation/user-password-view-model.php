<?php
namespace App\Microservice\Presentation\ViewModel\Use\Password;
?>
<?php
use App\Microservice\Core\Utils\Data\Response\ResponseData;
use App\Microservice\Domain\UseCase\User\Password\UserPasswordUseCase;
use App\Microservice\Schema\Domain\Model\User\Password\UserPasswordEntity;
use App\Microservice\Schema\Data\Model\User\Password\UserPasswordModel;
use App\Microservice\Schema\Data\Model\User\Password\UserPasswordInsertRequestModel;
use App\Microservice\Type\Action\Password\PasswordActionType;
use App\Microservice\Core\Utils\Type\Response\ResponseStatus;
use App\Microservice\Schema\Data\Model\User\Password\UserPasswordUpdateRequestModel;
use App\Microservice\Schema\Data\Model\User\Password\UserPasswordSelectRequestModel;
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

    public function executeViewModel(array $requestDataSet): ResponseData {
        $actionType = PasswordActionType::getByValue($requestDataSet["action_type"]);
        //
        if ($actionType == PasswordActionType::INSERT) {
            return $this->createPassword($requestDataSet);
        } else if ($actionType == PasswordActionType::UPDATE) {
            return $this->updatePassword($requestDataSet);
        } else if ($actionType == PasswordActionType::SELECT) {
            return $this->selectPassword($requestDataSet);
        }
        return new ResponseData("Missing requested action type", ResponseStatus::ERROR, $requestDataSet, 404);
    }

    public function createPassword(array $requestDataSet): ResponseData {
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
        $userPassword = new UserPasswordInsertRequestModel();
        //
        $dataVarList = $userPassword->getVarList();
        foreach ($dataVarList as $key) {
            if(array_key_exists($key, $requestDataSet)) {
                $userPassword->{$key} = $requestDataSet[$key];
            }
        }
        //
        return $this->useCase->createPassword($userPassword);
    }

    public function updatePassword(array $requestDataSet): ResponseData {
        $requiredFields = array(
            "user_id",
            "id",
            "action_type",
        );
        foreach ($requiredFields as $field) {
            if (empty($requestDataSet[$field])) {
                return new ResponseData("Missing required field: $field", ResponseStatus::ERROR, 400);
            }
        }
        //
        $userPassword = new UserPasswordUpdateRequestModel();
        //
        $dataVarList = $userPassword->getVarList();
        foreach ($dataVarList as $key) {
            if(array_key_exists($key, $requestDataSet)) {
                $userPassword->{$key} = $requestDataSet[$key];
            }
        }
        //
        return $this->useCase->updatePassword($userPassword);
    }

    public function selectPassword(array $requestDataSet): ResponseData {
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
        $userPassword = new UserPasswordSelectRequestModel();
        //
        $dataVarList = $userPassword->getVarList();
        foreach ($dataVarList as $key) {
            if(array_key_exists($key, $requestDataSet)) {
                $userPassword->{$key} = $requestDataSet[$key];
            }
        }
        //
        $columnRawData = $userPassword->columns;
        if (!empty($columnRawData)) {
            if (is_array($columnRawData)) {
                $userPassword->columns = $columnRawData;
            } else if (is_string($columnRawData)) {
                $decoded = json_decode($columnRawData, true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                    $userPassword->columns = $decoded;
                } else {
                    // Treat as comma-separated string
                    $userPassword->columns = array_map('trim', explode(',', $columnRawData));
                }
            }
        }
        //
        //return new ResponseData("Missing requested action type", ResponseStatus::ERROR, $userPassword, 404);
        return $this->useCase->selectPassword($userPassword);
    }

    public function getById(string $id): ResponseData {
        return $this->useCase->getPasswordById($id);
    }

    public function getByUserId(string $userId): ResponseData {
        return $this->useCase->getPasswordByUserId($userId);
    }
}
?>