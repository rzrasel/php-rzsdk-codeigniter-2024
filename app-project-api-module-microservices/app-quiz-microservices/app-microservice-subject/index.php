<?php
require_once("include.php");
?>
<?php
use App\Microservice\Protocol\State\Model\Request\Language\LanguageRequestData;
use App\Microservice\Presentation\Controller\Subject\SubjectController;
?>
<?php
//echo "Hi";
//$requestData = new SubjectRequestData("mysql", null);
$controller = new SubjectController();
$response = $controller->createLanguage($_POST);
echo $response->toJson();
?>