<?php
require_once("path-type.php");
require_once("find-working-directory.php");
require_once("include-path-config.php");
require_once("include-path-setup.php");
?>
<?php
use RzSDK\Include\Import\FindWorkingDirectory;
use RzSDK\Include\Import\PathType;
use RzSDK\Include\Import\IncludePathConfig;
use RzSDK\Include\Import\IncludePathSetup;
?>
<?php
global $pathTypeBeen;
$pathTypeBeen = PathType::RELATIVE_PATH;
?>
<?php
global $includePathConfig;
global $includePathSetup;
?>
<?php
$includePathConfig = IncludePathConfig::getInstance();
$includePathSetup = IncludePathSetup::getInstance();
?>
<?php
$includePathConfig
    ->setRemoveNumberOfDir(0, 2, 1);
$includePathSetup
    ->setPathConfigObject($includePathConfig)
    ->setPathTypeBeen($pathTypeBeen)
    ->setSDKDirName("global-library/rz-sdk-library")
    ->setProjectDirName("app-project-api-module-microservices")
    ->setWorkingDir(__DIR__)
    ->defineSDKPath()
    ->defineProjectPath()
    ->defineWorkingPath();
?>
<?php
require_once(RZ_SDK_BASE_PATH . "/autoloader/autoloader-config.php");
$includePathSetup->setAutoloaderConfigDir();
//echo RZ_SDK_BASE_PATH;
//echo DIRECTORY_SEPARATOR
?>
<?php
global $autoloaderConfig;
//$autoloaderConfig->setDirectories($realPath);
$results = $autoloaderConfig->getDirectories();
echo "<br />";
echo "<pre>" . print_r($results, true) . "</pre>";
echo "<br />";
?>
<?php
$startPath = __DIR__;
$targetFolder = "global-library/rz-sdk-library";
$relativePath = "../";
$results = FindWorkingDirectory::findTopLevelDirectory($startPath, $targetFolder, $relativePath);
echo "<pre>" . print_r($results, true) . "</pre>";
if($results) {
    $realPath = $results["realpath"];
    $relativePath = $results["relativepath"];
    $relativePath = dirname($relativePath);
    /*echo $relativePath;
    $directoryScanner = new DirectoryScanner();
    $results = $directoryScanner->scanDirectory($relativePath);
    echo "<pre>" . print_r($results, true) . "</pre>";*/
    global $autoloaderConfig;
    $autoloaderConfig->setDirectories($realPath);
    /*$autoloaderConfig->setDirectories("../");
    $autoloaderConfig->setDirectories("../../");*/
    //$autoloaderConfig->setDirectories("../../../");
    $results = $autoloaderConfig->getDirectories();
    //$autoloader = new Autoloader($autoloaderConfig);
    new findTest_class();
    //new FindTestClass();
} else {
    echo "Directory not found.\n";
}
?>
<?php
/*class FindTestClass {
}*/
?>
