<?php
namespace App\Microservice\Presentation\Controller\Language;
?>
<?php
use Core\Database;
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
// Usage
$requestData = new RequestData("mysql", null); // or "sqlite"
$controller = new LanguageController();
$response = $controller->handleRequest($requestData);
echo $response->getResponse();
?>