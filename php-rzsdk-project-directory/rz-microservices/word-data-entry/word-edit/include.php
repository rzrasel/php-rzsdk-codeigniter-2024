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
require_once("../word-search-module/include.php");
?>
<?php
$baseInclude = "model/";
require_once($baseInclude . "http-word-search-request-model.php");
require_once($baseInclude . "http-word-edit-request-model.php");
require_once($baseInclude . "database-word-edit-model.php");
?>
<?php
$baseInclude = "service/";
require_once($baseInclude . "word-search-activity-service.php");
require_once($baseInclude . "word-edit-service.php");
require_once($baseInclude . "word-edit-pre-database-service.php");
require_once($baseInclude . "word-edit-activity-service.php");
?>
<?php
$baseInclude = "module/";
require_once($baseInclude . "word-edit-requested-data-module.php");
?>
<?php
$baseInclude = "view/";
//require_once($baseInclude . "word-edit-form-view.php");
?>
<?php
$baseInclude = "";
require_once($baseInclude . "word-search-activity.php");
require_once($baseInclude . "word-edit-activity.php");
?>
