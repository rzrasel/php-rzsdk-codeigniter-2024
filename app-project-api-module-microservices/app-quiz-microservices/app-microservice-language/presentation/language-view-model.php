<?php
namespace App\Microservice\Presentation\ViewModel\Language;
?>
<?php
use App\Microservice\Domain\Repository\Language\LanguageRepository;
use App\Microservice\Domain\Service\Language\LanguageService;
use App\Microservice\Schema\Domain\Model\Language\LanguageEntity;
use App\Microservice\Model\Request\Language\LanguageRequestData;
use App\Microservice\Core\Utils\Data\Response\ResponseData;
use App\Microservice\Core\Utils\Type\Response\ResponseType;
use RzSDK\Data\Validation\Helper\ValidationHelper;

?>
<?php
class LanguageViewModel {
    private $repository;

    public function __construct(LanguageRepository $repository) {
        $this->repository = $repository;
    }

    public function createLanguage($requestDataSet): ResponseData {
        //$this->repository->createLanguage($language);
        //$requestDataModel = LanguageRequestData::dataValidation($requestDataSet);
        $languageRequestData = new LanguageRequestData();
        $requestDataModel = ValidationHelper::dataValidation($requestDataSet, $languageRequestData);
        if(!$requestDataModel["is_validate"]) {
            $message = $requestDataModel["message"];
            return new ResponseData($message, ResponseType::ERROR, $requestDataSet);
        }
        return new ResponseData("Language created successfully.", ResponseType::SUCCESS, $requestDataModel);
    }

    public function updateLanguage($requestData): void {
        //$this->repository->updateLanguage($language);
    }

    /*public function deleteLanguage(int $id): void {
        $this->service->deleteLanguage($id);
    }*/
}
?>