<?php
namespace RzSDK\Include\Import;
?>
<?php
require_once("path-type.php");
?>
<?php
class IncludePathConfig {
    private $pathTypeBeen = PathType::REAL_PATH;
    private $rzSDKDirName = "global-library/rz-sdk-library";
    private $projectDirName = "app-project-directory";
    private $workingDirName = "app-working-directory";
    private $rootDir = "";
    public function __construct(PathType $pathType = PathType::REAL_PATH) {
        $this->pathTypeBeen = $pathType;
    }

    public function setPathTypeBeen(PathType $pathType) {
        $this->pathTypeBeen = $pathType;
        return $this;
    }

    public function getPathTypeBeen(): PathType {
        return $this->pathTypeBeen;
    }

    public function setSDKDirName($sdkDirName) {
        $this->rzSDKDirName = $sdkDirName;
        return $this;
    }

    public function getSDKDirName(): string {
        return $this->rzSDKDirName;
    }

    public function setProjectDirName($projectDirName) {
        $this->projectDirName = $projectDirName;
        return $this;
    }

    public function getProjectDirName(): string {
        return $this->projectDirName;
    }

    public function setWorkingDirName($workingDirName) {
        $this->workingDirName = $workingDirName;
        return $this;
    }

    public function getWorkingDirName(): string {
        return $this->workingDirName;
    }

    public function getSDKDirPath($startPath = __DIR__, $relative = "../") {
        $targetFolder = $this->rzSDKDirName;
        $relativePath = $relative;
        return $this->findRootDir($startPath, $targetFolder, $relativePath);
    }

    public function getProjectDirPath($startPath = __DIR__, $relative = "../") {
        $targetFolder = $this->projectDirName;
        $relativePath = $relative;
        return $this->findRootDir($startPath, $targetFolder, $relativePath);
    }

    public function getWorkingDirPath($startPath = __DIR__, $relative = "../") {
        $targetFolder = $this->workingDirName;
        $relativePath = $relative;
        return $this->findRootDir($startPath, $targetFolder, $relativePath);
    }

    public function findRootDir($start, $target, $relative = "../") {
        $results = FindWorkingDirectory::findTopLevelDirectory($start, $target, $relative);
        if($results) {
            $realPath = trim(trim($results["realpath"], "\\"), "/");
            $relativePath = trim(trim($results["relativepath"], "\\"), "/");
            $this->rootDir = ($this->pathTypeBeen == PathType::REAL_PATH) ? $realPath : $relativePath;
            return $this->rootDir;
        }
        return false;
    }
}
?>
