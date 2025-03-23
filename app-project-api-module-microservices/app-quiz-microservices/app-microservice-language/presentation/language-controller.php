<?php
namespace App\Microservice\Presentation\Controller\Language;
?>
<?php
//use Core\Database;
use App\Microservice\Schema\Domain\Model\Language\LanguageEntity;
use App\Microservice\Core\Utils\Type\Database\DatabaseType;
use App\Microservice\Utils\Language\Data\Request\RequestData;
use App\Microservice\Core\Utils\Data\Response\ResponseData;
use App\Microservice\Core\Utils\Type\Response\ResponseType;
use App\Microservice\Data\Repository\Language\LanguageRepositoryImpl;
//use Domain\Services\LanguageService;
use App\Microservice\Presentation\ViewModel\Language\LanguageViewModel;
use App\Microservice\Presentation\View\Language\LanguageView;
use App\Microservice\Model\Request\Language\LanguageRequestData;
?>
<?php
class LanguageController {
    private $viewModel;
    private $view;

    public function __construct() {
        // Initialize database connection based on request
        $repository = new LanguageRepositoryImpl();
        $this->viewModel = new LanguageViewModel($repository);
        $this->view = new LanguageView($this->viewModel);
    }

    public function createLanguage($requestDataSet): ResponseData {
        //echo json_encode($requestData);
        if(empty($requestDataSet)) {
            return new ResponseData("Failed data empty", ResponseType::ERROR);
        }
        try {
            $response = $this->viewModel->createLanguage($requestDataSet);
            //return new ResponseData("Language created successfully.", ResponseType::SUCCESS, $response);
            return $response;
        } catch (\Exception $e) {
            return new ResponseData("Failed to create language: " . $e->getMessage(), ResponseType::ERROR);
        }
    }

    public function updateLanguage($requestDataSet): ResponseData {
        try {
            $this->viewModel->updateLanguage($requestDataSet);
            return new ResponseData("Language updated successfully.", ResponseType::SUCCESS);
        } catch (\Exception $e) {
            return new ResponseData("Failed to update language: " . $e->getMessage(), ResponseType::ERROR);
        }
    }

    /*public function deleteLanguage(int $id): ResponseData {
        try {
            $this->viewModel->deleteLanguage($id);
            return new ResponseData("Language deleted successfully.", ResponseType::SUCCESS);
        } catch (\Exception $e) {
            return new ResponseData("Failed to delete language: " . $e->getMessage(), ResponseType::ERROR);
        }
    }*/
}
?>