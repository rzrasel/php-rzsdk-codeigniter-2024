<?php
namespace App\Microservice\Presentation\Controller\Language;
?>
<?php
use Core\Database;
use App\Microservice\Schema\Domain\Model\Language\LanguageEntity;
use App\Microservice\Core\Utils\Type\Database\DatabaseType;
use App\Microservice\Utils\Language\Data\Request\RequestData;
use App\Microservice\Core\Utils\Data\Response\ResponseData;
use App\Microservice\Core\Utils\Type\Response\ResponseType;
use App\Microservice\Data\Repository\Language\LanguageRepositoryImpl;
use Domain\Services\LanguageService;
use App\Microservice\Presentation\ViewModel\Language\LanguageViewModel;
use App\Microservice\Presentation\View\Language\LanguageView;
?>
<?php
class LanguageController {
    private $db;
    private $repository;
    private $service;
    private $viewModel;
    private $view;

    public function __construct() {
        // Initialize database connection based on request
        $this->db = new Database(DatabaseType::MYSQL, "localhost", "root", "password", "database_name");
        $this->repository = new LanguageRepositoryImpl($this->db);
        $this->service = new LanguageService($this->repository);
        $this->viewModel = new LanguageViewModel($this->service);
        $this->view = new LanguageView($this->viewModel);
    }

    public function createLanguage(LanguageEntity $language): ResponseData {
        try {
            $this->viewModel->createLanguage($language);
            return new ResponseData("Language created successfully.", ResponseType::SUCCESS);
        } catch (\Exception $e) {
            return new ResponseData("Failed to create language: " . $e->getMessage(), ResponseType::ERROR);
        }
    }

    public function updateLanguage(LanguageEntity $language): ResponseData {
        try {
            $this->viewModel->updateLanguage($language);
            return new ResponseData("Language updated successfully.", ResponseType::SUCCESS);
        } catch (\Exception $e) {
            return new ResponseData("Failed to update language: " . $e->getMessage(), ResponseType::ERROR);
        }
    }

    public function deleteLanguage(int $id): ResponseData {
        try {
            $this->viewModel->deleteLanguage($id);
            return new ResponseData("Language deleted successfully.", ResponseType::SUCCESS);
        } catch (\Exception $e) {
            return new ResponseData("Failed to delete language: " . $e->getMessage(), ResponseType::ERROR);
        }
    }

    public function getLanguageById(int $id): ResponseData {
        try {
            $language = $this->viewModel->getLanguageById($id);
            return new ResponseData("Language retrieved successfully.", ResponseType::SUCCESS, $language);
        } catch (\Exception $e) {
            return new ResponseData("Failed to retrieve language: " . $e->getMessage(), ResponseType::ERROR);
        }
    }

    public function getAllLanguages(): ResponseData {
        try {
            $languages = $this->viewModel->getAllLanguages();
            return new ResponseData("Languages retrieved successfully.", ResponseType::SUCCESS, $languages);
        } catch (\Exception $e) {
            return new ResponseData("Failed to retrieve languages: " . $e->getMessage(), ResponseType::ERROR);
        }
    }
}
?>
<?php
class LanguageControllerV1 {
    public function handleRequest(RequestData $request): ResponseData {
        $databaseType = DatabaseType::getByName($request->databaseType);
        $db = new Database($databaseType, "localhost", "root", "password", "database_name", "path/to/sqlite.db");

        $repository = new LanguageRepositoryImpl($db);
        $service = new LanguageService($repository);
        $viewModel = new LanguageViewModel($service);
        $view = new LanguageView($viewModel);

        return $view->renderAllLanguages();
    }
}
?>
<?php
// Create a new language
$requestData = new RequestData("mysql", [
    'iso_code_2' => 'en',
    'iso_code_3' => 'eng',
    'name' => 'English'
]);
$controller = new LanguageController();
$languageEntity = new LanguageEntity();
$languageEntity->setIsoCode2($requestData->data['iso_code_2']);
$languageEntity->setIsoCode3($requestData->data['iso_code_3']);
$languageEntity->setName($requestData->data['name']);
// Call the controller to create the language
$response = $controller->createLanguage($languageEntity);
echo $response->toJson();

// Update an existing language
$requestData = new RequestData("mysql", [
    'id' => 1, // ID of the language to update
    'iso_code_2' => 'fr',
    'iso_code_3' => 'fra',
    'name' => 'French Updated'
]);
$controller = new LanguageController();
$languageEntity = new LanguageEntity();
$languageEntity->setId($requestData->data['id']);
$languageEntity->setIsoCode2($requestData->data['iso_code_2']);
$languageEntity->setIsoCode3($requestData->data['iso_code_3']);
$languageEntity->setName($requestData->data['name']);
// Call the controller to update the language
$response = $controller->updateLanguage($languageEntity);
echo $response->toJson();

// Delete a language
$requestData = new RequestData("mysql", [
    'id' => 1 // ID of the language to delete
]);
$controller = new LanguageController();
// Call the controller to delete the language
$response = $controller->deleteLanguage($requestData->data['id']);
echo $response->toJson();

// Get a language by ID
$requestData = new RequestData("mysql", [
    'id' => 1 // ID of the language to retrieve
]);
$controller = new LanguageController();
// Call the controller to get the language
$response = $controller->getLanguageById($requestData->data['id']);
echo $response->toJson();

// Get all languages
$requestData = new RequestData("mysql"); // No data needed for fetching all
$controller = new LanguageController();
// Call the controller to get all languages
$response = $controller->getAllLanguages();
echo $response->toJson();
?>
<?php
/*// Usage
$requestData = new RequestData("mysql", null); // or "sqlite"
$controller = new LanguageController();
$response = $controller->handleRequest($requestData);
echo $response->toJson();*/

/*// Assuming we're using a request parameter to decide the database type (MySQL or SQLite)
$dbType = $_GET['db_type'];

// Create instances of view and viewmodel
$languageRepository = new LanguageRepositoryImpl(Database::connect(DatabaseType::getByName($dbType)));
$languageViewModel = new LanguageViewModel($languageRepository);
$languageView = new LanguageView();
$languageController = new LanguageController($languageViewModel, $languageView);*/
?>