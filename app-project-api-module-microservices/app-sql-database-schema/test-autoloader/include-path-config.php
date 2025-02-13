<?php
namespace RzSDK\Include\Import;
?>
<?php
require_once("path-type.php");
require_once("find-working-directory.php");
?>
<?php
use RzSDK\Include\Import\FindWorkingDirectory;
use RzSDK\Include\Import\PathType;
?>
<?php
class IncludePathConfig {
    private static ?IncludePathConfig $instance = null;
    private $pathTypeBeen = PathType::REAL_PATH;
    private $startingPath = "";
    private $rzSDKDirName = "global-library/rz-sdk-library";
    private $projectDirName = "app-project-directory";
    private $workingDirName = "app-working-directory";
    private $rootDir = "";
    public static $constStartingPath = "CONST_STARTING_PATH";
    private function __construct(PathType $pathType = PathType::REAL_PATH) {
        $this->pathTypeBeen = $pathType;
    }

    public static function getInstance(PathType $pathType = PathType::REAL_PATH): IncludePathConfig {
        if (self::$instance === null || !isset(self::$instance)) {
            self::$instance = new self($pathType);
        }
        return self::$instance;
    }

    public function setPathTypeBeen(PathType $pathType) {
        $this->pathTypeBeen = $pathType;
        return $this;
    }

    public function getPathTypeBeen(): PathType {
        return $this->pathTypeBeen;
    }

    public function setStartingPath($startingPath = __DIR__) {
        $startingPath = trim(trim($startingPath, "\\"), "/");
        $this->startingPath = $startingPath;
        //defined(self::$constStartingPath) or define(self::$constStartingPath, $startingPath);
        defined("CONST_STARTING_PATH") or define("CONST_STARTING_PATH", $startingPath);
        return $this;
    }

    public function getStartingPath() {
        if (defined(CONST_STARTING_PATH)) {
            return CONST_STARTING_PATH;
        }
        return false;
    }

    public function setSDKDirName($sdkDirName) {
        $sdkDirName = trim(trim($sdkDirName, "\\"), "/");
        $this->rzSDKDirName = $sdkDirName;
        return $this;
    }

    public function getSDKDirName(): string {
        return $this->rzSDKDirName;
    }

    public function setProjectDirName($projectDirName) {
        $projectDirName = trim(trim($projectDirName, "\\"), "/");
        $this->projectDirName = $projectDirName;
        return $this;
    }

    public function getProjectDirName(): string {
        return $this->projectDirName;
    }

    public function setWorkingDirName($workingDirName) {
        $workingDirName = trim(trim($workingDirName, "\\"), "/");
        $this->workingDirName = $workingDirName;
        defined("CONST_WORKING_DIR_NAME") or define("CONST_WORKING_DIR_NAME", $workingDirName);
        return $this;
    }

    public function setRemoveNumberOfDir($sdkDir = 0, $projcetDir = 0, $workingDir = 0) {
        defined("CONST_SDK_DIR_REMOVE") or define("CONST_SDK_DIR_REMOVE", $sdkDir);
        defined("CONST_PROJECT_DIR_REMOVE") or define("CONST_PROJECT_DIR_REMOVE", $projcetDir);
        defined("CONST_WORKING_DIR_REMOVE") or define("CONST_WORKING_DIR_REMOVE", $workingDir);
        return $this;
    }

    public function getWorkingDirName(): string {
        return $this->workingDirName;
    }

    public function getSDKDirPath($startPath = __DIR__, $relative = "../") {
        $targetFolder = $this->rzSDKDirName;
        $relativePath = $relative;
        $path = $this->findRootDir($startPath, $targetFolder, $relativePath);
        if($this->pathTypeBeen == PathType::RELATIVE_PATH) {
            for ($i = 0; $i < CONST_SDK_DIR_REMOVE; $i++) {
                $path = $this->removeFirstDirectory($path);
            }
        }
        return $path;
    }

    public function getProjectDirPath($startPath = __DIR__, $relative = "../", $isRemove = true) {
        $targetFolder = $this->projectDirName;
        $relativePath = $relative;
        //return $this->findRootDir($startPath, $targetFolder, $relativePath);
        $path = $this->findRootDir($startPath, $targetFolder, $relativePath);
        if($isRemove) {
            $path = ($this->pathTypeBeen == PathType::REAL_PATH) ? $path : dirname($path);
        }
        $path = $this->findRootDir($startPath, $targetFolder, $relativePath);
        if($this->pathTypeBeen == PathType::RELATIVE_PATH) {
            for ($i = 0; $i < CONST_PROJECT_DIR_REMOVE; $i++) {
                $path = dirname($path);
            }
        }
        return $path;
    }

    public function getWorkingDirPath($startPath = __DIR__, $relative = "../", $isRemove = true) {
        $targetFolder = CONST_WORKING_DIR_NAME;
        $relativePath = $relative;
        $path = $this->findRootDir($startPath, $targetFolder, $relativePath);
        if($this->pathTypeBeen == PathType::RELATIVE_PATH) {
            for ($i = 0; $i < CONST_WORKING_DIR_REMOVE; $i++) {
                $path = dirname($path);
            }
        }
        return $path;
    }

    public function findRootDir($start, $target, $relative = "../") {
        $start = trim(trim($start, "\\"), "/");
        $results = FindWorkingDirectory::findTopLevelDirectory($start, $target, $relative);
        if($results) {
            $realPath = trim(trim($results["realpath"], "\\"), "/");
            $relativePath = trim(trim($results["relativepath"], "\\"), "/");
            $this->rootDir = ($this->pathTypeBeen == PathType::REAL_PATH) ? $realPath : $relativePath;
            return $this->rootDir;
        }
        return false;
    }

    function removeFirstDirectory($path) {
        $parts = explode(DIRECTORY_SEPARATOR, $path);
        if(empty($parts) || count($parts) < 2) {
            $parts = explode("/", $path);
        }
        //print_r($parts);
        // Remove the first part (first directory)
        array_shift($parts);
        // Reconstruct the path
        $path = implode("/", $parts);
        return trim(trim($path, "\\"), "/");
    }
}
?>
<?php
global $includePathConfig;
$includePathConfig = IncludePathConfig::getInstance();
?>
