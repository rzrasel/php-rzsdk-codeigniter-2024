<?php
namespace App\Core;
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
        $controllerClass = $this->controller . 'Controller';

        if (!class_exists($controllerClass)) {
            $controllerClass =  $this->controller . 'AppController';
            if (!class_exists($controllerClass)) {
                $this->jsonError("Controller '{$this->controller}' not found", 404);
            }
        }

        $controllerInstance = new $controllerClass();

        if (!method_exists($controllerInstance, $this->method)) {
            $this->jsonError("Method '{$this->method}' not found", 404);
        }

        call_user_func_array([$controllerInstance, $this->method], $this->params);
    }

    private function jsonError($message, $code = 400) {
        //http_response_code($code);
        header('Content-Type: application/json');
        echo json_encode(["error" => $message]);
        exit;
    }
}
?>
<?php
/*class Router {
    private $url;
    private $controller;
    private $method;
    private $params = [];

    public function __construct($url) {
        $this->url = $url;
        $this->parseUrl();
    }

    private function parseUrl() {
        $url = trim($this->url, '/');
        $url = filter_var($url, FILTER_SANITIZE_URL);
        $parts = explode('/', $url);
        echo "<pre>" . print_r($parts, true) . "</pre> " . __LINE__;

        // Get controller
        $this->controller = isset($parts[0]) && !empty($parts[0]) ?
            ucfirst(strtolower($parts[0])) . 'Controller' :
            DEFAULT_CONTROLLER . 'Controller';
        //echo $this->controller;

        // Get method
        $this->method = isset($parts[1]) && !empty($parts[1]) ?
            strtolower($parts[1]) :
            'index';

        // Get params
        $this->params = array_slice($parts, 2);
    }

    public function dispatch() {
        $controllerClass = 'App\\Controllers\\' . $this->controller;

        if (class_exists($controllerClass)) {
            $controller = new $controllerClass();

            // Check if method exists
            if (method_exists($controller, $this->method)) {
                // Call the method with parameters
                call_user_func_array([$controller, $this->method], $this->params);
            } else {
                // Method not found
                $this->jsonResponse(['error' => 'Method not found'], 404);
            }
        } else {
            // Controller not found
            $this->jsonResponse(['error' => 'Controller not found'], 404);
        }
    }

    private function jsonResponse($data, $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
}*/
?>
<?php
/*class Router {
    public static function route($url) {
        // Clean and split URL into segments
        $segments = explode("/", trim($url, "/"));

        // Default controller and action
        // Get controller from URL, if not use default controller
        $controller = isset($_GET["controller"]) ? ucfirst(strtolower($_GET["controller"])) . "Controller" : DEFAULT_CONTROLLER;
        echo $controller;

        // Get the method, default to index if not set
        $method = isset($_GET["method"]) ? $_GET["method"] : "index";

        // Get parameters from URL
        $params = isset($_GET["params"]) ? explode("/", $_GET["params"]) : [];

        // Check if the controller exists
        if (class_exists($controller)) {
            $controllerInstance = new $controller();

            // Check if the method exists
            if (method_exists($controllerInstance, $method)) {
                // Call the method with parameters
                call_user_func_array([$controllerInstance, $method], $params);
            } else {
                echo "Method '$method' not found in controller '$controller'.";
            }
        } else {
            echo "Controller '$controller' not found.";
        }
    }
}*/
?>