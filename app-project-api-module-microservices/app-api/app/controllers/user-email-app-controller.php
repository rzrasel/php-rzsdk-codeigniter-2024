<?php
namespace App\Api\Controllers;
?>
<?php
use App\Microservice\Core\Utils\Data\Response\ResponseData;
use App\Microservice\Core\Utils\Type\Response\ResponseStatus;
use App\Core\Universal\Utils\Data\Response\ResponseType;
use App\Microservice\Presentation\Controller\Use\Email\UserEmailController;
use \App\Core\BaseController;
?>
<?php
class UserEmailAppController extends BaseController {

    public function __construct() {
        parent::__construct();
        $this->setPostInput();
    }

    public function index() {
        if ($_SERVER['REQUEST_METHOD'] !== "POST") {
            http_response_code(200);
            $responseData = new ResponseData("Only POST method is allowed", ResponseStatus::ERROR, null);
            echo $responseData->toJson();
            exit;
        }
        //$_POST["response_type"] = ResponseType::JSON->value;
        $controller = new UserEmailController();
        //echo "<pre>" . print_r($_POST, true) . "</pre>";
        $response = $controller->executeController($_POST);
        echo $response->toJson();
    }

    public function show($id) {
        echo "Displaying data for ID: " . $id;
    }

    public function setPostInput() {
        header("Content-Type: application/json");
        if(empty($_POST)) {
            $rawData = file_get_contents("php://input");
            $inputData = json_decode($rawData, true);
            if(!empty($inputData)) {
                $_POST = $inputData;
            }
        }
    }
}
?>