//----- File: app-api/.htaccess -----

RewriteEngine On

# Prevent direct access to sensitive files
<Files "config.php">
Order Deny,Allow
Deny from all
</Files>

# Redirect non-existing files to index.php for routing
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php/$1 [L,QSA]

//----- File: app-api/app/config/config.php -----

<?php
define('DB_TYPE', 'mysql'); // Set 'sqlite' for SQLite or 'mysql' for MySQL
define('DB_HOST', 'localhost');
define('DB_NAME', 'test_db');
define('DB_USER', 'root');
define('DB_PASS', '');

// SQLite Configuration (for SQLite database)
define('DB_SQLITE_PATH', __DIR__ . '/database.db');

// Default Controller and Action
define('DEFAULT_CONTROLLER', 'HomeController');
define('DEFAULT_ACTION', 'index');
?>

//----- File: app-api/app/controllers/home-controller.php -----

<?php
use \App\Core\BaseController;
?>
<?php
class HomeController extends BaseController {

    public function index() {
        echo "Welcome to the HomeController!";
    }

    public function show($id) {
        echo "Displaying data for ID: " . $id;
    }
}
?>

//----- File: app-api/app/core/base-controller.php -----

<?php
namespace App\Core;
?>
<?php
?>
<?php
class BaseController {
    protected $model;

    public function __construct() {
        // Get the model name by taking the controller name and replacing "Controller" with "Model"
        $this->model = new BaseModel();
    }

    public function loadModel($modelName) {
        $modelFile = 'models/' . $modelName . '.php';
        if (file_exists($modelFile)) {
            require_once $modelFile;
            return new $modelName();
        }
        return null;
    }

    // Render JSON response for API requests
    public function renderJson($data) {
        header('Content-Type: application/json');
        echo json_encode($data);
    }

    // Render HTML view for standard web pages
    public function renderHtml($view, $data) {
        extract($data);
        require "app/views/{$view}.php";
    }
}
?>

//----- File: app-api/app/core/base-model.php -----

<?php
namespace App\Core;
?>
<?php
use App\Core\Database;
?>
<?php
class BaseModel {
    protected $db;

    public function __construct() {
        $this->db = Database::connect();
    }
}

//----- File: app-api/app/core/database.php -----

<?php
namespace App\Core;
?>
<?php
use PDO;
?>
<?php
class Database {
    public static function connect() {
        $dbType = DB_TYPE;
        $dbConnection = null;

        /*try {
            if ($dbType === 'mysql') {
                $dbConnection = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
            } elseif ($dbType === 'sqlite') {
                $dbConnection = new PDO("sqlite:" . DB_SQLITE_PATH);
            }
            $dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (\Exception $e) {
            die("Database connection failed: " . $e->getMessage());
        }*/

        return $dbConnection;
    }
}
?>

//----- File: app-api/app/core/router.php -----

<?php
namespace App\Core;
?>
<?php
class Router {
    public static function route($url) {
        // Clean and split URL into segments
        $segments = explode('/', trim($url, '/'));

        // Default controller and action
        // Get controller from URL, if not use default controller
        $controller = isset($_GET['controller']) ? ucfirst(strtolower($_GET['controller'])) . 'Controller' : DEFAULT_CONTROLLER;

        // Get the method, default to index if not set
        $method = isset($_GET['method']) ? $_GET['method'] : 'index';

        // Get parameters from URL
        $params = isset($_GET['params']) ? explode('/', $_GET['params']) : [];

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
}
?>

//----- File: app-api/index.php -----

<?php
require_once "app/config/config.php";
require_once "app/core/base-controller.php";
require_once "app/core/base-model.php";
require_once "app/core/database.php";
require_once "app/core/router.php";
?>
<?php
require_once "app/controllers/home-controller.php";
/*require_once "app/controllers/UserController.php";
require_once "app/controllers/ContactController.php";
require_once "app/models/UserModel.php";
require_once "app/models/ContactModel.php";*/
?>
<?php
use App\Core\Router;
?>
<?php
// Get the current URL
$url = $_SERVER["REQUEST_URI"];

// Call the router to handle the URL
Router::route($url);
?>


- make simple mvm php project
- mvm can use/handle api request also
- url translate like url/user, url/user/edit/13, url/contact, url/contact/add/13 etc
- dynamically access controller and model using url translate
- dynamically controller and model like class_exists, method_exists
- use base controller, base model, base database
- support multiple database like mysql, sqlite etc
- use url for controller and model data like: 1) url/user = user controller 2) url/user/edit/13 = user controller, update user id = 13
- $url controller and model data not case-sensitive
- provide proper .htaccess
- support both api request and html view data
- default controller and model is home
- default controller get from config file

*** refactor and optimize code if needed
*** don't break code consistency
*** must be code consistency
*** code or directory refactor if needed
*** code or directory refactor if better options
*** Don't change class name if not needed
*** Don't change unwanted codebase
*** provide full code
*** Provide Full Codebase
*** read full document properly