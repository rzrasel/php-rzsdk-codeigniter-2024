<?php
namespace App\Microservice\Presentation\ViewModel\Language;
?>
<?php
use App\Microservice\Domain\Repository\Language\LanguageRepository;
use App\Microservice\Domain\Service\Language\LanguageService;
use App\Microservice\Schema\Domain\Model\Language\LanguageEntity;
use App\Microservice\Protocol\State\Model\Request\Language\LanguageRequestData;
use App\Microservice\Core\Utils\Data\Response\ResponseData;
use App\Microservice\Core\Utils\Type\Response\ResponseStatus;
use RzSDK\Data\Validation\Helper\ValidationHelper;
use App\Microservice\Schema\Data\Model\Language\LanguageModel;
use App\Microservice\Data\Mapper\Language\LanguageRequestMapper;
use RzSDK\Identification\UniqueIntId;
use App\Microservice\Data\Mapper\Language\LanguageMapper;
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
        //|Trim whitespace from all request data fields|---------|
        foreach($requestDataSet as $key => $value) {
            $requestDataSet[$key] = trim($value);
        }
        //|Create an instance of LanguageRequestData for validation
        $languageRequestModel = new LanguageRequestData();
        //|Validate request data using a helper function|--------|
        $requestDataValidation = ValidationHelper::dataValidation($requestDataSet, $languageRequestModel);
        //|If validation fails, return an error response|--------|
        if(!$requestDataValidation["is_validate"]) {
            $message = $requestDataValidation["message"];
            return new ResponseData($message, ResponseStatus::ERROR, $requestDataSet);
        }
        //|Retrieve validated data model|------------------------|
        $languageRequestModel = $requestDataValidation["data_model"];
        //|Map request model to domain model|--------------------|
        $languageModel = LanguageRequestMapper::toModel($languageRequestModel);
        //|Generate a unique integer ID for the new language entry
        $uniqueIntId = new UniqueIntId();
        $sysUserId = $uniqueIntId->getSysUserId(SYS_USER_ID_STR);
        $languageModel->id = $uniqueIntId->getId();
        $languageModel->createdDate = date("Y-m-d H:i:s");
        $languageModel->modifiedDate = date("Y-m-d H:i:s");
        $languageModel->createdBy = $sysUserId;
        $languageModel->modifiedBy = $sysUserId;
        //|Convert domain model to data entity format|-----------|
        $languageEntity = LanguageMapper::toData($languageModel);
        //|Persist the new language entity in the database|------|
        $repoResponse = $this->repository->createLanguage($languageEntity);
        if(!$repoResponse->status) {
            return new ResponseData($repoResponse->message, ResponseStatus::ERROR, $requestDataSet);
        }
        return new ResponseData($repoResponse->message, ResponseStatus::SUCCESS, $requestDataSet);
    }

    public function updateLanguage($requestData): void {
        //$this->repository->updateLanguage($language);
    }

    /*public function deleteLanguage(int $id): void {
        $this->service->deleteLanguage($id);
    }*/
}
?>