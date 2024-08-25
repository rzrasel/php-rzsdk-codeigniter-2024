<?php
namespace RzSDK\Autoloader;
?>
<?php
defined("RZ_SDK_BASE_PATH") OR exit("No direct script access allowed");
defined("RZ_SDK_LIB_ROOT_DIR") OR exit("No direct script access allowed");
?>
<?php
require_once("project-autoloader-helper.php");
?>
<?php
use RzSDK\Autoloader\ProjectAutoloaderHelper;
use RzSDK\File\FileAssist;
?>
<?php
class ProjectAutoloader extends ProjectAutoloaderHelper {
    //
    private $directories;
    public $existedFilePath;
    //
    public function __construct($directories = "") {
        $this->directories = is_array($directories) ? $directories : [$directories];
        spl_autoload_register(array($this, "autoloadRegister"));
    }

    private function autoloadRegister($className) {
        /*echo "Libraries: " . __CLASS__ . " ---- " . __METHOD__;
        echo "<br />";
        echo $this->toNamespace($className);*/
        $class = parent::toNamespace($className);
        $class = parent::toLower(parent::fromCamelCase($class));
        $fileName = $class . ".php";
        $filePath = null;
        if(!empty($this->directories)) {
            //$pathList = parent::getDirectoryToPath("", $this->directories);
            //$pathList = parent::getDirectoryToPath("../rz-sdk-library", $this->directories);
            $pathList = parent::getDirectoryToPath(RZ_SDK_PROJECT_ROOT_DIR, $this->directories);
            /*echo "<br /><br /><br /><br />";
            $this->log($pathList);
            echo "<br /><br /><br /><br />";*/
            //echo "<br />";
            $filePath = parent::getExistedFilePath($pathList, $fileName);
            if(empty($filePath)) {
                //$pathList = parent::getDirectoryToPath("rz-sdk-library", $this->directories);
                $pathList = parent::getDirectoryToPath(RZ_SDK_LIB_ROOT_DIR, $this->directories);
                $filePath = parent::getExistedFilePath($pathList, $fileName);
            }
            /*echo "<br />";
            echo $filePath;
            echo "<br /><br /><br /><br />";*/
        } else {
            $filePath = parent::getExistedFilePath("", $fileName);
        }
        if(!empty($filePath)) {
            $this->existedFilePath = $filePath;
            //echo "Require Once File: {$this->existedFilePath}";
            foreach($filePath as $file) {
                require_once($file);
            }
        }
        $writePath = "rz-sdk-library/utils/site-url.txt";
        /*$writeData = "Test Data {$writePath}";
        parent::write($writePath, $writeData);
        $read = parent::read($writePath);
        echo "read:-------------{$read}";*/
        $writePath = str_replace(".txt", ".php", $writePath);
        //require_once("../rz-sdk-library/database/sqlite-connection.php");
        //require_once($writePath);
    }

    private function log($message) {
        echo "<pre>";
        if(is_array($message) || is_object($message)) {
            print_r($message);
        } else {
            print_r($message);
        }
        echo "</pre>";
    }
}
?>
<?php
$directoryList = array(
    "database-tables",
    "route",
    "utils",
    /*"database",
    "date-time",
    "debug-log",
    "detect-client",
    "encryption",
    "identification",
    "response",
    "sql-query-builder" => array(
        "module",
    ),
    "utils",
    "validation",*/
);
?>
<?php
$projectAutoloader = new ProjectAutoloader($directoryList);
//echo $projectAutoloader->existedFilePath;
?>
