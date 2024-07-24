<?php
namespace RzSDK\Import;
?>
<?php
$baseDirectory = rtrim(rtrim(dirname(dirname(__DIR__)), "\\"), "/");
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
require_once("../include.php");
?>
<?php
$baseInclude = "model/";
require_once($baseInclude . "http-word-entry-request-model.php");
require_once($baseInclude . "http-word-entry-response-model.php");
require_once($baseInclude . "database-word-entry-model.php");
?>
<?php
$baseInclude = "service/";
require_once($baseInclude . "word-entry-service.php");
require_once($baseInclude . "word-entry-activity-service.php");
?>
<?php
$baseInclude = "";
require_once($baseInclude . "word-entry-activity.php");
?>
