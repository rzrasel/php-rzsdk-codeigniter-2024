<?php
namespace RzSDK\Import;
?>
<?php
use RzSDK\Autoloader\AutoloaderConfig;
use RzSDK\URL\SiteUrl;
?>
<?php
require_once("app-include-libs/include-path-config.php");
?>
<?php
//|-----------|SET AND CONFIG AUTOLOADER CONFIG ARRAY|-----------|
//
/* global $pathTypeBeen;
$projectBaseDirectory = ($pathTypeBeen == PathType::REAL_PATH) ? RZ_PROJECT_ROOT_DIR . "/" : "";
global $autoloaderConfig;
$directoryList = array(
    $projectBaseDirectory . "" => array(
        "file-rename",
    ),
    "data-model" => array(
        "arabic-data-model",
        "english-data-model",
    ),
);
$autoloaderConfig->setDirectories($directoryList); */
/* $autoDirectories = $autoloaderConfig->getDirectories();
echo "<br />";
echo "<pre>" . print_r($autoDirectories, true) . "</pre>";
echo "<br />"; */
?>
<?php
//|----------------|INCLUDE AUTOLOADER.PHP FILE|-----------------|
//
$baseInclude = RZ_SDK_LIB_ROOT_DIR . "/autoloader";
require_once($baseInclude . "/autoloader.php");
?>
<?php
//use RzSDK\URL\SiteUrl;
?>
<?php
defined("ROOT_URL") or define("ROOT_URL", SiteUrl::getBaseUrl());
//echo SiteUrl::getBaseUrl();
?>