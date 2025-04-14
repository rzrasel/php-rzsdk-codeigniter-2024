<?php
namespace App\Microservice\Presentation\Controller\Subject;
?>
<?php
use App\Microservice\Data\Repository\Subject\SubjectRepositoryImpl;
use App\Microservice\Presentation\ViewModel\Subject\SubjectViewModel;
use App\Microservice\Core\Utils\Data\Response\ResponseData;
use App\Microservice\Core\Utils\Type\Response\ResponseStatus;
?>
<?php
class SubjectController {
    private $viewModel;
    private $view;

    public function __construct() {
        // Initialize database connection based on request
        $repository = new SubjectRepositoryImpl();
        $this->viewModel = new SubjectViewModel($repository);
        //$this->view = new LanguageView($this->viewModel);
    }

    public function createLanguage($requestDataSet): ResponseData {
        //echo json_encode($requestData);
        if(empty($requestDataSet)) {
            return new ResponseData("Failed data empty", ResponseStatus::ERROR);
        }
        try {
            $response = $this->viewModel->createLanguage($requestDataSet);
            //return new ResponseData("Language created successfully.", ResponseType::SUCCESS, $response);
            return $response;
        } catch (\Exception $e) {
            return new ResponseData("Failed to create language: " . $e->getMessage(), ResponseStatus::ERROR);
        }
    }
}
?>