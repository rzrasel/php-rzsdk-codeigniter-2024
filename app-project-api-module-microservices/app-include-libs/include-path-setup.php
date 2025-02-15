<?php
namespace RzSDK\Include\Import;
?>
<?php
require_once("path-type.php");
require_once("find-working-directory.php");
require_once("include-path-config.php");
?>
<?php
use RzSDK\Include\Import\FindWorkingDirectory;
use RzSDK\Include\Import\PathType;
use RzSDK\Include\Import\IncludePathConfig;
?>
<?php
global $pathTypeBeen;
$pathTypeBeen = PathType::REAL_PATH;
$pathTypeBeen = PathType::RELATIVE_PATH;
?>
<?php
class IncludePathSetup {
    private static ?IncludePathSetup $instance = null;
    private $pathTypeBeen = PathType::REAL_PATH;
    private IncludePathConfig $includePathConfig;
    private function __construct(PathType $pathType = PathType::REAL_PATH) {
        $this->pathTypeBeen = $pathType;
    }

    public static function getInstance(PathType $pathType = PathType::REAL_PATH): IncludePathSetup {
        if (self::$instance === null || !isset(self::$instance)) {
            self::$instance = new self($pathType);
        }
        return self::$instance;
    }

    public function setPathConfigObject(IncludePathConfig $includePathObject) {
        $this->includePathConfig = $includePathObject;
        if($this->includePathConfig != null) {
            $this->pathTypeBeen = $this->includePathConfig->getPathTypeBeen();
        }
        return $this;
    }

    public function setPathTypeBeen(PathType $pathType) {
        $this->pathTypeBeen = $pathType;
        if($this->includePathConfig == null) {
            return $this;
        }
        $this->includePathConfig->setPathTypeBeen($this->pathTypeBeen);
        return $this;
    }

    public function setSDKDirName($sdkDirName) {
        if($this->includePathConfig == null) {
            return $this;
        }
        $this->includePathConfig->setSDKDirName($sdkDirName);
        return $this;
    }

    public function setProjectDirName($projectDirName) {
        if($this->includePathConfig == null) {
            return $this;
        }
        $this->includePathConfig->setProjectDirName($projectDirName);
        return $this;
    }

    public function setWorkingDir($workingDir = __DIR__) {
        if($this->includePathConfig == null) {
            return $this;
        }
        $workingDir = trim(trim($workingDir, "\\"), "/");
        $workingDirName = trim(trim(basename($workingDir), "\\"), "/");
        $this->includePathConfig->setStartingPath($workingDir);
        $this->includePathConfig->setWorkingDirName($workingDirName);
        return $this;
    }

    public function defineSDKPath() {
        if($this->includePathConfig == null) {
            return $this;
        }
        $startingPath = $this->includePathConfig->getStartingPath();
        $rzSDKDirPath = $this->includePathConfig->getSDKDirPath($startingPath);
        defined("RZ_SDK_BASE_PATH") or define("RZ_SDK_BASE_PATH", $rzSDKDirPath);
        defined("RZ_SDK_LIB_ROOT_DIR") or define("RZ_SDK_LIB_ROOT_DIR", $rzSDKDirPath);
        return $this;
    }

    public function defineProjectPath($startPath = __DIR__, $relative = "../", $isRemove = true) {
        if($this->includePathConfig == null) {
            return $this;
        }
        $startingPath = $this->includePathConfig->getStartingPath();
        $projectPath = $this->includePathConfig->getProjectDirPath($startingPath, $relative, $isRemove);
        defined("RZ_PROJECT_ROOT_DIR") or define("RZ_PROJECT_ROOT_DIR", $projectPath);
        defined("RZ_PROJECT_ROOT_DIR") or define("RZ_PROJECT_ROOT_DIR", $projectPath);
        return $this;
    }

    public function defineWorkingPath($startPath = __DIR__, $relative = "../", $isRemove = true) {
        if($this->includePathConfig == null) {
            return $this;
        }
        $startingPath = $this->includePathConfig->getStartingPath();
        $workingPath = $this->includePathConfig->getWorkingDirPath($startingPath, $relative, $isRemove);
        defined("RZ_WORKING_ROOT_DIR") or define("RZ_WORKING_ROOT_DIR", $workingPath);
        defined("RZ_WORKING_ROOT_DIR") or define("RZ_WORKING_ROOT_DIR", $workingPath);
        return $this;
    }

    public function setAutoloaderConfigDir() {
        global $autoloaderConfig;
        $autoloaderConfig->setDirectories(RZ_SDK_LIB_ROOT_DIR);
        $autoloaderConfig->setDirectories(RZ_WORKING_ROOT_DIR);
        $autoloaderConfig->setDirectories(RZ_PROJECT_ROOT_DIR);
        return $this;
    }
}
?>
<?php
/*global $pathTypeBeen;
$pathTypeBeen = PathType::REAL_PATH;
$pathTypeBeen = PathType::RELATIVE_PATH;*/
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
$includePathSetup
    ->setPathConfigObject($includePathConfig)
    ->setPathTypeBeen($pathTypeBeen)
    ->setSDKDirName("global-library/rz-sdk-library")
    ->setProjectDirName("app-project-api-module-microservices")
    ->setWorkingDir(__DIR__);
/*$includePathSetup
    ->setPathConfigObject($includePathConfig)
    ->setPathTypeBeen($pathTypeBeen)
    ->setSDKDirName("global-library/rz-sdk-library")
    ->setProjectDirName("app-project-api-module-microservices")
    ->setWorkingDir(__DIR__)
    ->defineSDKPath()
    ->defineProjectPath()
    ->defineWorkingPath()
    ->setAutoloaderConfigDir();*/
?>
