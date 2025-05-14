<?php
use App\Microservice\Core\Utils\Data\Response\ResponseData;
use App\Microservice\Core\Utils\Type\Response\ResponseStatus;
use App\Microservice\Presentation\Controller\Use\Email\UserEmailController;
use \App\Core\BaseController;
?>
<?php
?>
<?php
class UserEmailAppController extends BaseController {

    public function index() {
        header("Content-Type: application/json");
        if ($_SERVER['REQUEST_METHOD'] !== "POST") {
            http_response_code(200);
            $responseData = new ResponseData("Only POST method is allowed", ResponseStatus::ERROR, null);
            echo $responseData->toJson();
            exit;
        }

        if(empty($_POST)) {
            $rawData = file_get_contents("php://input");
            $inputData = json_decode($rawData, true);
            if(!empty($inputData)) {
                $_POST = $inputData;
            }
        }
        $controller = new UserEmailController();
        $response = $controller->executeController($_POST);
        echo $response->toJson();
    }

    public function show($id) {
        echo "Displaying data for ID: " . $id;
    }
}
?>