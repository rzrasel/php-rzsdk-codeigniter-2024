<?php
namespace RzSDK\Import;
?>
<?php
use RzSDK\Autoloader\AutoloaderConfig;
?>
<?php
$baseDirectory = rtrim(rtrim(__DIR__, "\\"), "/");
//echo $baseDirectory;
defined("RZ_SDK_BASE_PATH") or define("RZ_SDK_BASE_PATH", $baseDirectory);
defined("RZ_SDK_LIB_ROOT_DIR") or define("RZ_SDK_LIB_ROOT_DIR", $baseDirectory . "/rz-sdk-library");
?>
<?php
$baseInclude = RZ_SDK_LIB_ROOT_DIR . "/autoloader";
require_once($baseInclude . "/autoloader-config.php");
?>
<?php
global $autoloaderConfig;
$directoryList = array(
    "libs" => array(
        "file-rename",
    ),
    "data-model" => array(
        "arabic-data-model",
        "english-data-model",
    ),
);
$autoloaderConfig->setDirectories($directoryList);
?>
<?php
require_once($baseInclude . "/autoloader.php");
?>
<?php
//echo "<pre>" . print_r($directories,1)  . "</pre>";
?>
<?php
/* defined("RZ_LOCAL_LIBS") or define("RZ_LOCAL_LIBS", $baseDirectory . "/libs");
$baseInclude = RZ_LOCAL_LIBS . "/autoloader";
require_once($baseInclude . "/autoloader.php"); */
?>
<?php

//$baseInclude = "rz-sdk-library/";
//require_once("dir-to-array.php");
//require_once("rename-file.php");

?>