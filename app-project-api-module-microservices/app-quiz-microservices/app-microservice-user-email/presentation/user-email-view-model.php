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

    public function executeViewModel(array $request): ResponseData {
        if (empty($request['action_type'])) {
            return new ResponseData(
                "Missing action_type",
                ResponseStatus::ERROR,
                null,
                400
            );
        }

        $actionType = EmailActionType::getByValue($request['action_type']);
        if (!$actionType) {
            return new ResponseData(
                "Invalid action_type",
                ResponseStatus::ERROR,
                null,
                400
            );
        }

        return match ($actionType) {
            EmailActionType::INSERT => $this->createEmail($request),
            EmailActionType::SELECT => $this->selectEmail($request),
            default => new ResponseData(
                "Unsupported action type",
                ResponseStatus::ERROR,
                null,
                400
            )
        };
    }

    private function createEmail(array $request): ResponseData {
        $required = array(
            "user_id",
            "action_type",
        );
        foreach ($required as $field) {
            if (empty($request[$field])) {
                return new ResponseData(
                    "Missing required field: $field",
                    ResponseStatus::ERROR,
                    null,
                    400
                );
            }
        }

        $model = new UserEmailInsertRequestModel();
        $this->mapRequestToModel($request, $model);
        return $this->useCase->createEmail($model);
    }

    private function selectEmail(array $request): ResponseData {
        $model = new UserEmailSelectRequestModel();
        $this->mapRequestToModel($request, $model);

        if (isset($request['columns'])) {
            $model->columns = $request['columns'];
        }

        return $this->useCase->selectEmail($model);
    }

    private function mapRequestToModel(array $request, object $model): void {
        foreach ($model->getVarList() as $property) {
            if (array_key_exists($property, $request)) {
                $model->{$property} = $request[$property];
            }
        }
    }
}
?>