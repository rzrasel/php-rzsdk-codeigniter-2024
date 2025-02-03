<?php
namespace RzSDK\Include\Import;
?>
<?php
$rootDirectory = __DIR__;
//$baseProjectDirectory = "app-project-module-microservices";
$baseProjectRelativeDir = "";
$isForceProjectRelative = false;
//$baseSDKLibsDir = "global-library";
//$sdkLibsDir = "rz-sdk-library";
$sdkLibsRelativeDir = "";
$isForceSDKRelative = false;
?>
<?php
defined("INCLUDE_PROJECT_ROOT_DIR") or define("INCLUDE_PROJECT_ROOT_DIR", $rootDirectory);
//defined("INCLUDE_PROJECT_DIR_NAME") or define("INCLUDE_PROJECT_DIR_NAME", $baseProjectDirectory);
defined("INCLUDE_PROJECT_RELATIVE_DIR") or define("INCLUDE_PROJECT_RELATIVE_DIR", $baseProjectRelativeDir);
defined("INCLUDE_PROJECT_RELATIVE_FORCE") or define("INCLUDE_PROJECT_RELATIVE_FORCE", $isForceProjectRelative);
//defined("INCLUDE_BASE_SDK_LIBS_DIR_NAME") or define("INCLUDE_BASE_SDK_LIBS_DIR_NAME", $baseSDKLibsDir);
//defined("INCLUDE_SDK_LIBS_DIR_NAME") or define("INCLUDE_SDK_LIBS_DIR_NAME", $sdkLibsDir);
defined("INCLUDE_SDK_LIBS_RELATIVE_DIR") or define("INCLUDE_SDK_LIBS_RELATIVE_DIR", $sdkLibsRelativeDir);
defined("INCLUDE_SDK_RELATIVE_FORCE") or define("INCLUDE_SDK_RELATIVE_FORCE", $isForceSDKRelative);
?>
<?php
require_once("../app-include-libs/include-path-setup.php");
?>
<?php
use RzSDK\Autoloader\AutoloaderConfig;
?>
<?php
global $pathTypeBeen;
$baseProjectDirectory = INCLUDE_PROJECT_ROOT_DIR;
$baseProjectDirectory = ($pathTypeBeen == PathType::REAL_PATH) ? $baseProjectDirectory . "/" : "";
?>
<?php
global $autoloaderConfig;
$directoryList = array(
    $baseProjectDirectory . "app-database-tables-schema" => array(
        "app-language-info-schema",
        "app-user-info-schema",
        "app-user-email-schema",
        "app-user-token-info-schema",
    ),
);
$autoloaderConfig->setDirectories($directoryList);
/*$autoDirectories = $autoloaderConfig->getDirectories();
echo "<br />";
echo "<pre>" . print_r($autoDirectories, true) . "</pre>";*/
?>
<?php
require_once("../include.php");
?>
<?php
use RzSDK\URL\SiteUrl;
?>
<?php
defined("BASE_URL") or define("BASE_URL", SiteUrl::getBaseUrl());
defined("ROOT_URL") or define("ROOT_URL", SiteUrl::getBaseUrl());
//echo SiteUrl::getBaseUrl();
?>
