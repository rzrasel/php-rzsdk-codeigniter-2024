<?php
namespace RzSDK\Autoloader;
?>
<?php
defined("RZ_SDK_BASE_PATH") or exit("No direct script access allowed");
defined("RZ_SDK_LIB_ROOT_DIR") or exit("No direct script access allowed");
?>
<?php
require_once("autoloader-helper.php");
require_once("autoloader-config.php");
require_once("directory-scanner.php");
require_once("convert-case-to-file-name.php");
require_once("find-working-directory.php");
require_once("find-array-key-by-value.php");
?>
<?php
use RzSDK\Autoloader\AutoloaderHelper;
use RzSDK\Autoloader\AutoloaderConfig;
use RzSDK\Autoloader\ConvertCaseToFileName;
use RzSDK\Autoloader\DirectoryScanner;
use RzSDK\Autoloader\FindWorkingDirectory;
use RzSDK\Autoloader\FindArrayKeyByValue;
?>
<?php
class Autoloader extends AutoloaderHelper{
    private static ?Autoloader $instance = null;
    private $directories;
    private $isFileWrite = false;
    private AutoloaderConfig $autoloaderConfig;
    private function __construct(AutoloaderConfig $autoloaderConfig = new AutoloaderConfig()) {
        $directories = $autoloaderConfig->getDirectories();
        $this->directories = is_array($directories) ? $directories : [$directories];
        $this->isFileWrite = $autoloaderConfig->getIsFileWrite();
        //echo "<pre>" . print_r($this->directories, true) . "</pre>";
        //echo "{$this->isFileWrite}";
        //$this->scanDirectories();
        $this->autoloaderConfig = $autoloaderConfig;
        spl_autoload_register(array($this, "autoloadRegister"));
    }

    public static function getInstance(AutoloaderConfig $autoloaderConfig = new AutoloaderConfig()): Autoloader {
        if(self::$instance === null || !isset(self::$instance)) {
            self::$instance = new self($autoloaderConfig);
        }
        return self::$instance;
    }

    private function autoloadRegister($className) {
        $class = parent::toNamespace($className);
        //$class = parent::toLower(parent::fromCamelCase($class));
        //$fileName = $class . ".php";
        /*$data = (new ConvertCaseToFileName())->toFileName($class);
        echo "<pre>" . print_r($data, true) . "</pre>";*/
        //echo $class;
        if($this->isFileWrite) {
            $result = parent::readCachePath($this->autoloaderConfig, $class);
            //echo $result;
            if($result) {
                return;
            }
        }
        //echo "<pre>" . print_r($this->directories, true) . "</pre>";
        $fileNameList = parent::getClassToFilePath($class, $this->directories, $this->autoloaderConfig, ".php");
        //echo "<pre>" . print_r($fileNameList, true) . "</pre>";
        //echo "{$this->isFileWrite}";
        if(!empty($fileNameList)) {
            //echo $fileName;
            foreach ($fileNameList as $fileName) {
                require_once($fileName);
            }
        }
    }

    public function scanDirectories() {
        $dirList = [];
        foreach($this->directories as $directory) {
            $directoryScanner = new DirectoryScanner();
            $results = $directoryScanner->scanDirectory($directory);
            $dirList = array_merge($dirList, $results);
        }
        echo "<pre>" . print_r($dirList, true) . "</pre>";
    }

    public function directoryFile($directories) {}
}
?>
<?php
global $autoloaderConfig;
$autoloader = Autoloader::getInstance($autoloaderConfig);
//echo $autoloader->existedFilePath;
?>
