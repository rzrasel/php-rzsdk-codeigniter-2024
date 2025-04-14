<?php
namespace App\Microservice\Presentation\ViewModel\Subject;
?>
<?php
use App\Microservice\Domain\Repository\Subject\SubjectRepository;
use App\Microservice\Core\Utils\Data\Response\ResponseData;
use App\Microservice\Core\Utils\Type\Response\ResponseStatus;
use App\Microservice\Protocol\State\Model\Request\Subject\SubjectRequestData;
use RzSDK\Data\Validation\Helper\ValidationHelper;
use App\Microservice\Data\Mapper\Subjec\SubjectRequestMapper;
use RzSDK\Identification\UniqueIntId;
use App\Microservice\Data\Mapper\Subject\SubjectMapper;
?>
<?php
class SubjectViewModel {
    private $repository;

    public function __construct(SubjectRepository $repository)
    {
        $this->repository = $repository;
    }

    public function createLanguage($requestDataSet): ResponseData {
        //|Trim whitespace from all request data fields|---------|
        foreach($requestDataSet as $key => $value) {
            $requestDataSet[$key] = trim($value);
        }
        //|Create an instance of SubjectRequestData for validation
        $subjectRequestModel = new SubjectRequestData();
        //|Validate request data using a helper function|--------|
        $requestDataValidation = ValidationHelper::dataValidation($requestDataSet, $subjectRequestModel);
        //|If validation fails, return an error response|--------|
        if(!$requestDataValidation["is_validate"]) {
            $message = $requestDataValidation["message"];
            return new ResponseData($message, ResponseStatus::ERROR, $requestDataSet);
        }
        //|Retrieve validated data model|------------------------|
        $subjectRequestModel = $requestDataValidation["data_model"];
        //|Map request model to domain model|--------------------|
        $subjectModel = SubjectRequestMapper::toModel($subjectRequestModel);
        //|Generate a unique integer ID for the new language entry
        $uniqueIntId = new UniqueIntId();
        $sysUserId = $uniqueIntId->getSysUserId(SYS_USER_ID_STR);
        $subjectModel->id = $uniqueIntId->getId();
        $subjectModel->createdDate = date("Y-m-d H:i:s");
        $subjectModel->modifiedDate = date("Y-m-d H:i:s");
        $subjectModel->createdBy = $sysUserId;
        $subjectModel->modifiedBy = $sysUserId;
        //|Convert domain model to data entity format|-----------|
        $subjectEntity = SubjectMapper::toData($subjectModel);
        //|Persist the new language entity in the database|------|
        $repoResponse = $this->repository->createLanguage($subjectEntity);
        if(!$repoResponse->status) {
            return new ResponseData($repoResponse->message, ResponseStatus::ERROR, $requestDataSet);
        }
        return new ResponseData($repoResponse->message, ResponseStatus::SUCCESS, $requestDataSet);
    }
}