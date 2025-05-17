<?php
namespace App\Api\Controllers;
?>
<?php
use App\Microservice\Core\Utils\Data\Response\ResponseData;
use App\Microservice\Core\Utils\Type\Response\ResponseStatus;
use App\Microservice\Presentation\Controller\Use\Password\UserPasswordController;
use \App\Core\BaseController;
?>
<?php
class UserPasswordAppController extends BaseController {

    public function __construct() {
        parent::__construct();
        $this->setPostInput();
    }

    public function index() {
        header("Content-Type: application/json");
        if ($_SERVER['REQUEST_METHOD'] !== "POST") {
            http_response_code(200);
            $responseData = new ResponseData("Only POST method is allowed", ResponseStatus::ERROR, null);
            echo $responseData->toJson();
            exit;
        }

        $controller = new UserPasswordController();
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