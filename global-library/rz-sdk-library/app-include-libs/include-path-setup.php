<?php
namespace RzSDK\Include\Import;
?>
<?php
require_once("path-type.php");
require_once("include-path-config.php");
?>
<?php
$rootDirectory = __DIR__;
$baseProjectDirectory = "app-project-module-microservices";
$baseProjectRelativeDir = "";
$isForceProjectRelative = false;
$baseSDKLibsDir = "global-library";
$sdkLibsDir = "rz-sdk-library";
$sdkLibsRelativeDir = "";
$isForceSDKRelative = false;
?>
<?php
defined("INCLUDE_PROJECT_ROOT_DIR") or define("INCLUDE_PROJECT_ROOT_DIR", $rootDirectory);
defined("INCLUDE_PROJECT_DIR_NAME") or define("INCLUDE_PROJECT_DIR_NAME", $baseProjectDirectory);
defined("INCLUDE_PROJECT_RELATIVE_DIR") or define("INCLUDE_PROJECT_RELATIVE_DIR", $baseProjectRelativeDir);
defined("INCLUDE_PROJECT_RELATIVE_FORCE") or define("INCLUDE_PROJECT_RELATIVE_FORCE", $isForceProjectRelative);
defined("INCLUDE_BASE_SDK_LIBS_DIR_NAME") or define("INCLUDE_BASE_SDK_LIBS_DIR_NAME", $baseSDKLibsDir);
defined("INCLUDE_SDK_LIBS_DIR_NAME") or define("INCLUDE_SDK_LIBS_DIR_NAME", $sdkLibsDir);
defined("INCLUDE_SDK_LIBS_RELATIVE_DIR") or define("INCLUDE_SDK_LIBS_RELATIVE_DIR", $sdkLibsRelativeDir);
defined("INCLUDE_SDK_RELATIVE_FORCE") or define("INCLUDE_SDK_RELATIVE_FORCE", $isForceSDKRelative);
?>
<?php
global $pathTypeBeen;
$pathTypeBeen = PathType::RELATIVE_PATH;
$rootDirectory = INCLUDE_PROJECT_ROOT_DIR;
$baseProjectDirectory = INCLUDE_PROJECT_DIR_NAME;
$baseProjectRelativeDir = INCLUDE_PROJECT_RELATIVE_DIR;
$isForceProjectRelative = INCLUDE_PROJECT_RELATIVE_FORCE;
$baseSDKLibsDir = INCLUDE_BASE_SDK_LIBS_DIR_NAME;
$sdkLibsDir = INCLUDE_SDK_LIBS_DIR_NAME;
$sdkLibsRelativeDir = INCLUDE_SDK_LIBS_RELATIVE_DIR;
$isForceSDKRelative = INCLUDE_SDK_RELATIVE_FORCE;
?>
<?php
//require_once("app-include-libs/include-path-config.php");
?>
<?php
definedDirProjectPath($rootDirectory, $baseProjectDirectory, $isForceProjectRelative, $baseProjectRelativeDir);
definedDirSDKLibsPath($rootDirectory, $baseSDKLibsDir, $sdkLibsDir, $isForceSDKRelative, $sdkLibsRelativeDir);
importAutoLoaderConfig();
/*echo "<br />";
echo "Project Base Directory Name: " . RZ_PROJECT_ROOT_DIR . PHP_EOL;
echo "<br />";
echo "SDK Base Directory Name: " . RZ_SDK_LIB_ROOT_DIR . PHP_EOL;*/
?>
