<?php
namespace RzSDK\Import;
?>
<?php
$directory = __DIR__;
$baseDirectory = rtrim(rtrim($directory, "\\"), "/");
//
defined("RZ_PROJECT_DIR_INITIALIZATION") or define("RZ_PROJECT_DIR_INITIALIZATION", $baseDirectory);
//
$baseDirectory = dirname(RZ_PROJECT_DIR_INITIALIZATION);
$baseDirectory = rtrim(rtrim($directory, "\\"), "/");
$baseDirectory = "..";
/* echo "<br />"; 
echo $baseDirectory; */
?>
<?php
require_once("{$baseDirectory}/include/include-path-config.php");
?>
<?php
/* echo "<br />";
echo RZ_PROJECT_ROOT_DIR;
echo "<br />";
echo RZ_SDK_BASE_PATH;
echo "<br />";
echo RZ_SDK_LIB_ROOT_DIR;
echo "<br />"; */
?>
<?php
//|-----------|SET AND CONFIG AUTOLOADER CONFIG ARRAY|-----------|
//
global $pathTypeBeen;
$projectBaseDirectory = ($pathTypeBeen == PathType::REAL_PATH) ? RZ_PROJECT_ROOT_DIR . "/" : "../";
global $autoloaderConfig;
$directoryList = array(
    $projectBaseDirectory . "user-auth-token" => array(
        "model",
        "core",
    ),
);
$autoloaderConfig->setDirectories($directoryList);
/* $autoDirectories = $autoloaderConfig->getDirectories();
echo "<br />";
echo "<pre>" . print_r($autoDirectories, true) . "</pre>";
echo "<br />"; */
?>
<?php
global $pathTypeBeen;
$projectBaseDirectory = ($pathTypeBeen == PathType::REAL_PATH) ? RZ_PROJECT_ROOT_DIR : "..";
//echo $pathTypeBeen->value;
require_once("{$projectBaseDirectory}/include.php");
?>