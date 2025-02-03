<?php
namespace RzSDK\Include\Import;
?>
<?php
require_once("app-include-libs/include-path-setup.php");
?>
<?php
use RzSDK\Autoloader\AutoloaderConfig;
?>
<?php
global $pathTypeBeen;
$baseProjectDirectory = INCLUDE_PROJECT_DIR_NAME;
$baseProjectDirectory = ($pathTypeBeen == PathType::REAL_PATH) ? $baseProjectDirectory : "";
?>
<?php
global $autoloaderConfig;
$directoryList = array(
    $baseProjectDirectory . "user-auth-token" => array(
        "model",
        "core",
    ),
);
$autoloaderConfig->setDirectories($directoryList);
/*$autoDirectories = $autoloaderConfig->getDirectories();
echo "<br />";
echo "<pre>" . print_r($autoDirectories, true) . "</pre>";
echo "<br />";*/
?>
<?php
//|----------------|INCLUDE AUTOLOADER.PHP FILE|-----------------|
//
$baseInclude = RZ_SDK_LIB_ROOT_DIR . "/autoloader";
require_once($baseInclude . "/autoloader.php");
?>
<?php
use RzSDK\URL\SiteUrl;
?>
<?php
defined("BASE_URL") or define("BASE_URL", SiteUrl::getBaseUrl());
defined("ROOT_URL") or define("ROOT_URL", SiteUrl::getBaseUrl());
//echo SiteUrl::getBaseUrl();
?>
