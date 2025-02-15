<?php
namespace RzSDK\Include\Import;
?>
<?php
require_once("app-include-libs/path-type.php");
require_once("app-include-libs/find-working-directory.php");
require_once("app-include-libs/include-path-config.php");
require_once("app-include-libs/include-path-setup.php");
?>
<?php
use RzSDK\Include\Import\FindWorkingDirectory;
use RzSDK\Include\Import\PathType;
use RzSDK\Include\Import\IncludePathConfig;
use RzSDK\Include\Import\IncludePathSetup;
?>
<?php
global $pathTypeBeen;
$pathTypeBeen = PathType::REAL_PATH;
$pathTypeBeen = PathType::RELATIVE_PATH;
?>
<?php
$sdkDirName = "global-library/rz-sdk-library";
$projectDir = __DIR__;
$projectDirName = "app-project-api-module-microservices";
$workingDir = $projectDir;
$workingDirName = basename($workingDir);
?>
<?php
global $includePathConfig;
global $includePathSetup;
?>
<?php
if($includePathConfig == null || !isset($includePathConfig)) {
    $includePathConfig = IncludePathConfig::getInstance();
}
$includePathSetup = IncludePathSetup::getInstance();
?>
<?php
if($pathTypeBeen == PathType::RELATIVE_PATH) {
    $includePathConfig
        ->setRemoveNumberOfDir(0, 2, 2);
} else {
    $includePathConfig
        ->setRemoveNumberOfDir(0, 0, 0);
}
?>
<?php
$includePathSetup
    ->setPathConfigObject($includePathConfig)
    ->setPathTypeBeen($pathTypeBeen)
    ->setSDKDirName($sdkDirName)
    ->setProjectDirName($projectDirName)
    ->setWorkingDir($workingDir)
    ->defineSDKPath()
    ->defineProjectPath()
    ->defineWorkingPath();
?>
<?php
require_once(RZ_SDK_BASE_PATH . "/autoloader/autoloader-config.php");
$includePathSetup->setAutoloaderConfigDir();
?>
<?php
global $autoloaderConfig;
$autoloaderConfig
    ->setCacheFilePath(RZ_PROJECT_ROOT_DIR)
    ->setIsFileWrite(false);
//$autoloaderConfig->setDirectories($realPath);
/*$results = $autoloaderConfig->getDirectories();
echo "<br />";
echo "<pre>" . print_r($results, true) . "</pre>";
echo "<br />";*/
?>
<?php
$baseInclude = RZ_SDK_BASE_PATH . "/autoloader";
require_once(RZ_SDK_BASE_PATH . "/autoloader/autoloader.php");
?>
<?php
//use RzSDK\URL\SiteUrl;
?>
<?php
/*$baseUrl = SiteUrl::getBaseUrl();
defined("JOB_BASE_URL") or define("JOB_BASE_URL", $baseUrl);
defined("JOB_ROOT_URL") or define("JOB_ROOT_URL", $baseUrl);
$result = FindWorkingDirectory::findBaseUrl($baseUrl, $projectDirName);
$projectBaseUrl = ($result) ? $result : $baseUrl;
defined("BASE_URL") or define("BASE_URL", $projectBaseUrl);
defined("ROOT_URL") or define("ROOT_URL", $projectBaseUrl);*/
/*echo JOB_BASE_URL;
echo "<br />";
echo BASE_URL;*/
?>
