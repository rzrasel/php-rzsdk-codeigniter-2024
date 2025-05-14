<?php
namespace RzSDK\Include\Import;
?>
<?php
$workingDir = __DIR__;
$workingDirName = basename($workingDir);
defined("CONST_STARTING_PATH") or define("CONST_STARTING_PATH", $workingDir);
defined("CONST_WORKING_DIR_NAME") or define("CONST_WORKING_DIR_NAME", $workingDirName);
?>
<?php
require_once("../include.php");
//require_once("../../include.php");
?>
<?php
//echo "==================================Language";
?>
<?php
global $workingBaseUrl;
$workingBaseUrl = BASE_URL . "/" . CONST_WORKING_DIR_NAME;
defined("WORKING_BASE_URL") or define("WORKING_BASE_URL", $workingBaseUrl);
defined("WORKING_ROOT_URL") or define("WORKING_ROOT_URL", $workingBaseUrl);
?>
<?php
/*defined("DB_PATH") or define("DB_PATH", "database");
defined("DB_FILE") or define("DB_FILE", "database-schema-database.sqlite");
defined("DB_FULL_PATH") or define("DB_FULL_PATH", "" . DB_PATH . "/" . DB_FILE);*/
?>