<?php
namespace RzSDK\Import;
?>
<?php
$baseDirectory = rtrim(rtrim(dirname(__DIR__), "\\"), "/");
defined("RZ_SDK_BASE_PATH") or define("RZ_SDK_BASE_PATH", $baseDirectory);
defined("RZ_SDK_LIB_ROOT_DIR") or define("RZ_SDK_LIB_ROOT_DIR", $baseDirectory . "/rz-sdk-library");
?>
<?php
$baseDirectory = rtrim(__DIR__, "\\");
defined("RZ_SDK_PROJECT_ROOT_DIR") or define("RZ_SDK_PROJECT_ROOT_DIR", $baseDirectory . "");
//echo RZ_SDK_PROJECT_ROOT_DIR;
?>
<?php
$projectDirectory = rtrim(rtrim(dirname($_SERVER["PHP_SELF"]), "\\"), "/");
$projectDirectoryUp = rtrim(rtrim(dirname($projectDirectory), "\\"), "/");
$projectDirectory = trim(trim(str_replace($projectDirectoryUp, "", $projectDirectory), "\\"), "/");
$baseDirectory = $baseDirectory . "/" . $projectDirectory;
//echo $baseDirectory;
defined("RZ_PROJECT_BASE_PATH") or define("RZ_PROJECT_BASE_PATH", $baseDirectory);
?>
<?php
/*echo RZ_SDK_BASE_PATH;
echo "<br />";
echo __LINE__;
echo "<br />";
echo RZ_PROJECT_BASE_PATH;
echo "<br />";
echo __LINE__;
echo "<br />";*/
?>
<?php
defined("DB_PATH") or define("DB_PATH", "database");
defined("DB_FILE") or define("DB_FILE", "word-pronunciation-mapping.sqlite");
defined("DB_FULL_PATH") or define("DB_FULL_PATH", "../" . DB_PATH . "/" . DB_FILE);
?>
<?php
$directoryList = array(
    "database-tables",
    "html-view",
    "route",
    "shared",
    "utils",
    "universal" => array(
        "word-meaning-search",
    ),
    "word-meaning-entry" => array(
        "activity",
        "model",
        "service",
    ),
    "word-meaning-search" => array(
        "activity",
        "model",
        "service",
    ),
    "word-meaning-side-by-side" => array(
        "activity",
        "model",
        "service",
    ),
    /*"book-token-entry" => array(
        "activity",
        "model",
        "service",
    ),
    "book-name-entry" => array(
        "activity",
        "model",
        "service",
    ),
    "book-info-entry" => array(
        "activity",
        "model",
        "service",
    ),
    "character-table-token-entry" => array(
        "activity",
        "model",
        "service",
    ),
    "character-table-map-entry" => array(
        "activity",
        "model",
        "service",
    ),*/
);
defined("RZ_PROJECT_DIR_LIST") or define("RZ_PROJECT_DIR_LIST", $directoryList);
?>
<?php
$baseInclude = "rz-sdk-library/";
//
$baseDirectory = RZ_SDK_BASE_PATH;
$baseInclude = $baseDirectory . "/rz-sdk-library/autoloader/";
require_once($baseInclude . "autoloader.php");
?>
<?php
//
$baseInclude = "autoloader/";
require_once($baseInclude . "project-autoloader.php");
?>
<?php
use RzSDK\URL\SiteUrl;
?>
<?php
$baseUrl = rtrim(SiteUrl::getBaseUrl(), "/");
defined("BASE_URL") or define("BASE_URL", $baseUrl);
?>
<?php
$baseInclude = RZ_SDK_BASE_PATH . "/utils/";
require_once($baseInclude . "database-table-utils/include.php");
$baseInclude = RZ_SDK_BASE_PATH . "/utils/";
require_once($baseInclude . "launch-response.php");
$baseInclude = RZ_SDK_BASE_PATH . "/utils/service-listener/";
require_once($baseInclude . "service-listener.php");
?>
