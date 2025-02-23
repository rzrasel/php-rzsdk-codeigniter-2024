<?php
namespace RzSDK\Import;
?>
<?php
use RzSDK\Autoloader\AutoloaderConfig;
use RzSDK\URL\SiteUrl;
?>
<?php
$directory = dirname(dirname(__DIR__));
$baseDirectory = rtrim(rtrim($directory, "\\"), "/");
//$baseDirectory = trim("../../", "/");
//echo $baseDirectory;
defined("RZ_SDK_BASE_PATH") or define("RZ_SDK_BASE_PATH", $baseDirectory);
defined("RZ_SDK_LIB_ROOT_DIR") or define("RZ_SDK_LIB_ROOT_DIR", trim("{$baseDirectory}/global-library/rz-sdk-library", "/"));
//defined("RZ_SDK_LIB_ROOT_DIR") or define("RZ_SDK_LIB_ROOT_DIR", "../rz-sdk-library");
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
    "sql-database-schema",
    /* "../utils-library" => array(
        "database-table-utils",
    ), */
);
$autoloaderConfig->setDirectories($directoryList);
?>
<?php
//require_once($baseInclude . "/autoloader.php");
require_once("../include.php");
?>
<?php
//use RzSDK\URL\SiteUrl;
?>
<?php
defined("ROOT_URL") or define("ROOT_URL", SiteUrl::getBaseUrl());
//echo SiteUrl::getBaseUrl();
?>