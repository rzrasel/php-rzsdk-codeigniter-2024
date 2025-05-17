<?php
require_once("include.php");
require_once "app/config/config.php";
?>
<?php
use RzSDK\URL\SiteUrl;
use App\Core\Router;
?>
<?php
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