<?php
namespace RzSDK\Import;
?>
<?php
use RzSDK\Autoloader\AutoloaderConfig;
use RzSDK\URL\SiteUrl;
?>
<?php
enum PathType: string {
    case REAL_PATH = "realpath";
    case RELATIVE_PATH = "relativepath";
    public static function getByValue($value): self {
        foreach(self::cases() as $props) {
            /* if ($case->name === $enumName) {
                return $case;
            } */
            if($props->value === $value) {
                return $props;
            }
        }
        return self::REAL_PATH;
    }
}
$pathTypeBeen = PathType::RELATIVE_PATH;
?>
<?php
$directory = __DIR__;
$baseDirectory = rtrim(rtrim($directory, "\\"), "/");
defined("RZ_PROJECT_DIR_INITIALIZATION") or define("RZ_PROJECT_DIR_INITIALIZATION", $baseDirectory);
$baseDirectory = RZ_PROJECT_DIR_INITIALIZATION;
//$baseDirectory = trim("../../", "/");
/* echo "<br />";
echo $baseDirectory; */
?>
<?php
require_once("find-directory.php");
?>
<?php
//
//|------|STATICALLY SET MODULE OR PROJECT DIRECTORY NAME|-------|
//
//$startDir = __DIR__;
$startDir = $baseDirectory;
$projectTargetDir = "project-module-microservices";
$projectBaseDirectories = findNamedDirectory($startDir, $projectTargetDir);
$realPath = $projectBaseDirectories["realpath"];
$relativePath = $projectBaseDirectories["relativepath"];
$projectBaseDirectory = ($pathTypeBeen == PathType::REAL_PATH) ? $realPath : "";
$projectBaseDirectory = rtrim(rtrim($projectBaseDirectory, "\\"), "/");
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
defined("RZ_PROJECT_ROOT_DIR") or define("RZ_PROJECT_ROOT_DIR", $projectBaseDirectory);
defined("RZ_SDK_BASE_PATH") or define("RZ_SDK_BASE_PATH", $projectBaseDirectory);
//
//
//|--------|STATICALLY SET RZ-SDK-LIBRARY DIRECTORY NAME|--------|
//
//$startDir = __DIR__;
//$startDir = $baseDirectory;
$startDir = RZ_PROJECT_DIR_INITIALIZATION;
$libsTargetDir = "global-library/rz-sdk-library";
$libsBaseDirectories = findNamedDirectory($startDir, $libsTargetDir);
$realPath = trim($libsBaseDirectories["realpath"], "/");
$relativePath = trim($libsBaseDirectories["relativepath"], "/");
$libsBaseDirectory = ($pathTypeBeen == PathType::REAL_PATH) ? $realPath : "{$relativePath}/{$libsTargetDir}";
$libsBaseDirectory = rtrim(rtrim($libsBaseDirectory, "\\"), "/");
//
//|-------|DEFINED CONSTANT RZ-SDK-LIBRARY DIRECTORY NAME|-------|
//
//defined("RZ_SDK_LIB_ROOT_DIR") or define("RZ_SDK_LIB_ROOT_DIR", $baseDirectory . "/rz-sdk-library");
//defined("RZ_SDK_LIB_ROOT_DIR") or define("RZ_SDK_LIB_ROOT_DIR", trim("{$libsBaseDirectory}/global-library/rz-sdk-library", "/"));
defined("RZ_SDK_LIB_ROOT_DIR") or define("RZ_SDK_LIB_ROOT_DIR", trim($libsBaseDirectory, "/"));
//
//
/* echo "<br />";
echo RZ_PROJECT_ROOT_DIR;
echo "<br />";
echo RZ_SDK_BASE_PATH;
echo "<br />";
echo RZ_SDK_LIB_ROOT_DIR;
echo "<br />"; */
?>
<?php
//|-------------|INCLUDE AUTOLOADER-CONFIG.PHP FILE|-------------|
//
$baseInclude = RZ_SDK_LIB_ROOT_DIR . "/autoloader";
require_once($baseInclude . "/autoloader-config.php");
?>