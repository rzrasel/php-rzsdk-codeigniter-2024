<?php
header("Content-Type: application/json");
?>
<?php
require_once("include.php");
?>
<?php
use App\Microservice\Core\Utils\Data\Response\ResponseData;
use App\Microservice\Core\Utils\Type\Response\ResponseStatus;
use App\Microservice\Presentation\Controller\Use\Mobile\UserMobileController;
?>
<?php
//header("Content-Type: application/json");
if ($_SERVER['REQUEST_METHOD'] !== "POST") {
    http_response_code(200);
    $responseData = new ResponseData("Only POST method is allowed", ResponseStatus::ERROR, null, 405);
    echo $responseData->toJson();
    exit;
}
?>
<?php
if(empty($_POST)) {
    $rawData = file_get_contents("php://input");
    $inputData = json_decode($rawData, true);
    if(!empty($inputData)) {
        $_POST = $inputData;
    }
}
$controller = new UserMobileController();
$response = $controller->executeController($_POST);
echo $response->toJson();
?>