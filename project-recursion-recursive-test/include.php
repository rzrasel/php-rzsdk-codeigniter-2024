<?php
namespace RzSDK\Import;
?>
<?php
use RzSDK\Autoloader\AutoloaderConfig;
use RzSDK\URL\SiteUrl;
?>
<?php
$directory = dirname(__DIR__);
$baseDirectory = rtrim(rtrim($directory, "\\"), "/");
$baseDirectory = trim("../", "/");;
//echo $baseDirectory;
defined("RZ_SDK_BASE_PATH") or define("RZ_SDK_BASE_PATH", $baseDirectory);
//defined("RZ_SDK_LIB_ROOT_DIR") or define("RZ_SDK_LIB_ROOT_DIR", $baseDirectory . "/rz-sdk-library");
defined("RZ_SDK_LIB_ROOT_DIR") or define("RZ_SDK_LIB_ROOT_DIR", trim("{$baseDirectory}/global-library/rz-sdk-library", "/"));
/* echo "<br />";
echo RZ_SDK_BASE_PATH;
echo "<br />";
echo RZ_SDK_LIB_ROOT_DIR;
echo "<br />"; */
?>
<?php
$baseInclude = RZ_SDK_LIB_ROOT_DIR . "/autoloader";
require_once($baseInclude . "/autoloader-config.php");
?>
<?php
global $autoloaderConfig;
$directoryList = array(
    /* "libs" => array(
        "file-rename",
    ),
    "data-model" => array(
        "arabic-data-model",
        "english-data-model",
    ), */
);
$autoloaderConfig->setDirectories($directoryList);
?>
<?php
require_once($baseInclude . "/autoloader.php");
?>
<?php
//use RzSDK\URL\SiteUrl;
?>
<?php
defined("ROOT_URL") or define("ROOT_URL", SiteUrl::getBaseUrl());
//echo SiteUrl::getBaseUrl();
?>
<?php
defined("DB_PATH") or define("DB_PATH", "database");
defined("DB_FILE") or define("DB_FILE", "recursion-recursive.sqlite");
defined("DB_FULL_PATH") or define("DB_FULL_PATH", "../" . DB_PATH . "/" . DB_FILE);
//echo DB_FULL_PATH;
?>