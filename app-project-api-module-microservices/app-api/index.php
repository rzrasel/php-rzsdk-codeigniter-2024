<?php
require_once("include.php");
/*echo BASE_URL;
echo "<br />";*/
?>
<?php
require_once "app/config/config.php";
//require_once "app/core/base-controller.php";
//require_once "app/core/base-model.php";
//require_once "app/core/database.php";
//require_once "app/core/router.php";
?>
<?php
//require_once "app/controllers/home-controller.php";
/*require_once "app/controllers/UserController.php";
require_once "app/controllers/ContactController.php";
require_once "app/models/UserModel.php";
require_once "app/models/ContactModel.php";*/
?>
<?php
use RzSDK\URL\SiteUrl;
use App\Core\Router;
?>
<?php
// Get the current URL
/*$url = $_SERVER["REQUEST_URI"];
echo $url;*/
$fullUrl = SiteUrl::getFullUrl();

// Call the router to handle the URL
$router = new Router($fullUrl);
$router->dispatch();
/*$homeController = new HomeController();
$homeController->showBy(1);*/
?>