<?php
require_once("include.php");
?>
<?php
use App\Microservice\Model\Request\Language\LanguageRequestData;
use App\Microservice\Presentation\Controller\Language\LanguageController;
?>
<?php
//echo "Hi";
$requestData = new LanguageRequestData("mysql", null);
$controller = new LanguageController();
$response = $controller->createLanguage($_POST);
echo $response->toJson();
?>