<?php
namespace App\Core;
?>
<?php
use App\Core\Universal\Utils\Data\Response\ResponseData;
use App\Core\Universal\Utils\Data\Response\ResponseStatus;
?>
<?php
class Router {
    private $controller;
    private $method;
    private $params;

    public function __construct() {
        $url = $_GET['url'];
        $this->parseUrl($url);
    }

    private function parseUrl($url) {
        $url = trim($url, '/');
        $url = filter_var($url, FILTER_SANITIZE_URL);
        $parts = explode('/', $url);
        //echo "<pre>" . print_r($parts, true) . "</pre>";
        $this->controller = DEFAULT_CONTROLLER;
        if (!empty($parts[0])) {
            $this->controller = ucwords(str_replace('-', ' ', strtolower($parts[0])));
            $this->controller = str_replace(' ', '', $this->controller);
        }
        $this->method = isset($parts[1]) ? strtolower($parts[1]) : DEFAULT_ACTION;
        $this->params = array_slice($parts, 2);
    }

    public function dispatch() {
        $namespace = 'App\\Api\\Controllers\\';
        $controllerClass = $namespace . $this->controller . 'Controller';

        $isExistApiController = true;

        if (!class_exists($controllerClass)) {
            $controllerClass =  $namespace . $this->controller . 'AppController';
            if (!class_exists($controllerClass)) {
                $isExistApiController = false;
                $this->jsonError(
                    $message = "Controller '{$controllerClass}' not found",
                    $status = ResponseStatus::ERROR,
                    $data = null,
                    $code = 404,
                    $line = __LINE__
                );
            }
        }

        if(!$isExistApiController) {
            $namespace = 'App\\View\\Controllers\\';
            $controllerClass = $namespace . $this->controller . 'Controller';
            if (!class_exists($controllerClass)) {
                $controllerClass =  $namespace . $this->controller . 'AppController';
                if (!class_exists($controllerClass)) {
                    $this->jsonError(
                        $message = "Controller '{$controllerClass}' not found",
                        $status = ResponseStatus::ERROR,
                        $data = null,
                        $code = 404,
                        $line = __LINE__
                    );
                }
            }
        }

        $controllerInstance = new $controllerClass();

        if (!method_exists($controllerInstance, $this->method)) {
            $this->jsonError(
                $message = "Method '{$this->method}' not found",
                $status = ResponseStatus::ERROR,
                $data = null,
                $code = 404,
                $line = __LINE__
            );
        }

        call_user_func_array([$controllerInstance, $this->method], $this->params);
    }

    private function jsonError($message, ResponseStatus $status, $data, $code = 400, $line = 0) {
        //http_response_code($code);
        $responseData = new ResponseData($message, $status, $data, $code, $line);
        header('Content-Type: application/json');
        //echo json_encode(["error" => $message]);
        echo json_encode($responseData);
        exit;
    }
}
?>