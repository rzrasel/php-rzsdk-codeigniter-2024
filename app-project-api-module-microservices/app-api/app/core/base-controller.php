<?php
namespace App\Core;
?>
<?php
?>
<?php
class BaseController {
    protected $model;

    public function __construct($modelName = null) {
        if ($modelName) {
            $this->loadModel($modelName);
        }
    }

    public function loadModel($modelName) {
        $modelClass = "models/" . $modelName . ".php";
        if (class_exists($modelClass)) {
            $this->model = new $modelClass();
        }
    }

    // Render JSON response for API requests
    public function renderJson($data) {
        header("Content-Type: application/json");
        echo json_encode($data);
    }

    // Render HTML view for standard web pages
    public function renderHtml($view, $data) {
        extract($data);
        require "app/views/{$view}.php";
    }
}
?>