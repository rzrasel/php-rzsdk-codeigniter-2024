<?php
namespace RzSDK\Include\Import;
?>
<?php
require_once("find-working-directory.php");
require_once("path-type.php");
?>
<?php
?>
<?php
//
//|------|STATICALLY SET MODULE OR PROJECT DIRECTORY NAME|-------|
//
function definedDirProjectPath($rootDirectory, $baseDirectoryName, $forceRelativePath = false, $relativePath = "") {
    global $pathTypeBeen;
    $projectBaseDirectory = FindWorkingDirectory::findDirectory($rootDirectory, $baseDirectoryName);
    $realPath = $projectBaseDirectory["realpath"];
    $relativePathFound = $projectBaseDirectory["relativepath"];
    if($forceRelativePath) {
        $relativePathFound = $relativePath;
    }
    $realPath = rtrim(rtrim($realPath, "\\"), "/");
    //$relativePathFound = rtrim(rtrim($relativePathFound, "\\"), "/");
    $projectBaseDirectory = ($pathTypeBeen == PathType::REAL_PATH) ? $realPath : $relativePathFound;
    /*echo "<br />";
    echo "Project Base Directory Name: " . $projectBaseDirectory . PHP_EOL;*/
    defined("RZ_PROJECT_ROOT_DIR") or define("RZ_PROJECT_ROOT_DIR", $projectBaseDirectory);
    defined("RZ_SDK_BASE_PATH") or define("RZ_SDK_BASE_PATH", $projectBaseDirectory);
}
/*$rootDirectory = __DIR__;
$baseProjectDirectoryName = "app-project-module-microservices";
echo "<br />";
echo "Root Directory: {$rootDirectory}" . PHP_EOL;
echo "<br />";
echo "Base Project Directory Name: {$baseProjectDirectoryName}" . PHP_EOL;

$projectBaseDirectory = getProjectDirectory($rootDirectory, $baseProjectDirectoryName);
echo "<br />";
echo "Project Base Directory Name: " . print_r($projectBaseDirectory, true) . PHP_EOL;*/
/*$projectTargetDir = "project-module-microservices";
$projectBaseDirectories = findNamedDirectory($startDir, $projectTargetDir);
$realPath = $projectBaseDirectories["realpath"];
$relativePath = $projectBaseDirectories["relativepath"];
$projectBaseDirectory = ($pathTypeBeen == PathType::REAL_PATH) ? $realPath : "";
$projectBaseDirectory = rtrim(rtrim($projectBaseDirectory, "\\"), "/");*/
/* echo $pathTypeBeen->value;
echo $pathTypeBeen->name; */
//$projectBaseDirectory = ($pathTypeBeen == PathType::REAL_PATH) ? $projectBaseDirectory : "";
/* echo "<br />";
echo $baseDirectory;
echo "<br />";
echo $projectBaseDirectory; */
//
//|-----|DEFINED CONSTANT MODULE OR PROJECT DIRECTORY NAME|------|
//
/*defined("RZ_PROJECT_ROOT_DIR") or define("RZ_PROJECT_ROOT_DIR", $projectBaseDirectory);
defined("RZ_SDK_BASE_PATH") or define("RZ_SDK_BASE_PATH", $projectBaseDirectory);*/
//
//
//|--------|STATICALLY SET RZ-SDK-LIBRARY DIRECTORY NAME|--------|
//
//$startDir = __DIR__;
//$startDir = $baseDirectory;
/*global $pathTypeBeen;
$startDir = RZ_PROJECT_DIR_INITIALIZATION;
$libsTargetDir = "global-library/rz-sdk-library";
$libsBaseDirectories = findNamedDirectory($startDir, $libsTargetDir);
$realPath = trim($libsBaseDirectories["realpath"], "/");
$relativePath = trim($libsBaseDirectories["relativepath"], "/");
$libsBaseDirectory = ($pathTypeBeen == PathType::REAL_PATH) ? $realPath : "{$relativePath}/{$libsTargetDir}";
$libsBaseDirectory = rtrim(rtrim($libsBaseDirectory, "\\"), "/");*/
//
//|-------|DEFINED CONSTANT RZ-SDK-LIBRARY DIRECTORY NAME|-------|
//
//defined("RZ_SDK_LIB_ROOT_DIR") or define("RZ_SDK_LIB_ROOT_DIR", $baseDirectory . "/rz-sdk-library");
//defined("RZ_SDK_LIB_ROOT_DIR") or define("RZ_SDK_LIB_ROOT_DIR", trim("{$libsBaseDirectory}/global-library/rz-sdk-library", "/"));
/*defined("RZ_SDK_LIB_ROOT_DIR") or define("RZ_SDK_LIB_ROOT_DIR", trim($libsBaseDirectory, "/"));*/
//
//
?>
<?php
function definedDirSDKLibsPath($rootDirectory, $baseDirectoryName, $baseSDKDir, $forceRelativePath = false, $relativePath = "") {
    global $pathTypeBeen;
    $projectBaseDirectory = FindWorkingDirectory::findDirectory($rootDirectory, $baseDirectoryName);
    $realPath = $projectBaseDirectory["realpath"];
    $relativePathFound = $projectBaseDirectory["relativepath"];
    if($forceRelativePath) {
        $relativePathFound = $relativePath;
    }
    $realPath = rtrim(rtrim($realPath, "\\"), "/");
    //$relativePathFound = rtrim(rtrim($relativePathFound, "\\"), "/");
    $projectBaseDirectory = ($pathTypeBeen == PathType::REAL_PATH) ? $realPath : $relativePathFound . $baseDirectoryName;
    /*echo "<br />";
    echo "Project Base Directory Name: " . $projectBaseDirectory . PHP_EOL;*/
    $projectBaseDirectory = trim(trim($projectBaseDirectory, "\\"), "/");
    $baseSDKDir = trim(trim($baseSDKDir, "\\"), "/");
    $libsBaseDirectory = $projectBaseDirectory . "/" . $baseSDKDir;
    $libsBaseDirectory = trim(trim($libsBaseDirectory, "\\"), "/");
    defined("RZ_SDK_LIB_ROOT_DIR") or define("RZ_SDK_LIB_ROOT_DIR", $libsBaseDirectory);
}
?>
<?php
//|-------------|INCLUDE AUTOLOADER-CONFIG.PHP FILE|-------------|
//
function importAutoLoaderConfig() {
    $baseInclude = RZ_SDK_LIB_ROOT_DIR . "/autoloader";
    /*echo "<br />";
    echo "SDK Base Include Directory Name: " . $baseInclude . PHP_EOL;*/
    require_once($baseInclude . "/autoloader-config.php");
}
/*$baseInclude = RZ_SDK_LIB_ROOT_DIR . "/autoloader";
require_once($baseInclude . "/autoloader-config.php");*/
?>