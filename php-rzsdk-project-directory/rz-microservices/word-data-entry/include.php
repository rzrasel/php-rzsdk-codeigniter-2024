<?php
namespace RzSDK\Import;
?>
<?php
$baseDirectory = rtrim(rtrim(dirname(__DIR__), "\\"), "/");
defined("RZ_SDK_BASE_PATH") or define("RZ_SDK_BASE_PATH", $baseDirectory);
defined("RZ_SDK_LIB_ROOT_DIR") or define("RZ_SDK_LIB_ROOT_DIR", $baseDirectory . "/rz-sdk-library");
?>
<?php
$projectDirectory = rtrim(rtrim(dirname($_SERVER["PHP_SELF"]), "\\"), "/");
$projectDirectoryUp = rtrim(rtrim(dirname($projectDirectory), "\\"), "/");
$projectDirectory = trim(trim(str_replace($projectDirectoryUp, "", $projectDirectory), "\\"), "/");
$baseDirectory = $baseDirectory . "/" . $projectDirectory;
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
defined("DB_FILE") or define("DB_FILE", "word-database.sqlite");
defined("DB_FULL_PATH") or define("DB_FULL_PATH", "../" . DB_PATH . "/" . DB_FILE);
?>
<?php
$baseInclude = "rz-sdk-library/";
//
$baseDirectory = RZ_SDK_BASE_PATH;
$baseInclude = $baseDirectory . "/rz-sdk-library/autoloader/";
require_once($baseInclude . "autoloader.php");
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
<?php
$baseInclude = "database-tables/";
require_once($baseInclude . "db-word-table.php");
require_once($baseInclude . "tbl-language.php");
require_once($baseInclude . "tbl-language-query.php");
require_once($baseInclude . "dictionary-word.php");
require_once($baseInclude . "dictionary-word-query.php");
require_once($baseInclude . "dictionary-word-meaning.php");
require_once($baseInclude . "dictionary-word-meaning-query.php");
?>
<?php
//
$baseInclude = "utils/";
require_once($baseInclude . "side-link.php");
require_once($baseInclude . "id-generator.php");
require_once($baseInclude . "database-language-options-utils.php");
require_once($baseInclude . "alert-message-box.php");
?>
<?php
//
$baseInclude = "";
//require_once($baseInclude . "side-link.php");
?>