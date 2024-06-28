<?php
namespace RzSDK\Autoloader;
// defined("RZ_SDK_BASEPATH") or define("RZ_SDK_BASEPATH", trim(trim(__DIR__, "/")));
defined("RZ_SDK_BASEPATH") OR exit("No direct script access allowed");
defined("RZ_SDK_WRAPPER") OR exit("No direct script access allowed");
require_once(RZ_SDK_WRAPPER . "/autoloader/autoloader-helper.php");
require_once(RZ_SDK_WRAPPER . "../file-assist.php");
?>
<?php
use RzSDK\Autoloader\AutoloaderHelper;
use RzSDK\File\FileAssist;
?>
<?php
class Autoloader extends AutoloaderHelper {
    private $dirList = array();

    public function __construct($basePath, $dirList = array()) {
        $this->dirList = $dirList;
        spl_autoload_register(array($this, "autoloadRegister"));
    }

    public static function autoload() {
        /* echo "Libraries: " . __CLASS__ . " ---- " . __METHOD__;
        echo "<br />";
        echo RZ_SDK_BASEPATH;
        echo "<br />"; */
        // spl_autoload_register([__CLASS__, "autoloadRegister"]);
    }

    private function autoloadRegister($className) {
        /* echo "Libraries: " . __CLASS__ . " ---- " . __METHOD__;
        echo "<br />"; */
        // $helper = new AutoloaderHelper();
        $classNameTemp = parent::toNamespace($className);
        if($this->readPath($classNameTemp)) {
            return;
        }
        //echo $classNameTemp;
        $filePath = parent::adaptFileName($className);
        parent::prepareExistedFile(RZ_SDK_WRAPPER, $this->dirList, $filePath, ".php");
        /* echo "<pre>";
        print_r($filePath);
        echo "</pre>";
        print($helper->getExistedFile()); */
        if(file_exists(parent::getExistedFile())) {
            require_once(parent::getExistedFile());
            $this->writePath($classNameTemp, parent::getExistedFile());
        }
    }

    private function writePath($className, $filePath) {
        $dirPathFile = RZ_SDK_DIR_FILE_PATH;
        $fileAssist = new FileAssist();
        $fileData = json_encode(array());
        if(file_exists($dirPathFile)) {
            $fileData = $fileAssist->read($dirPathFile);
        }
        $filePathList = json_decode($fileData, true);
        if(!empty($filePathList)) {
            if(!in_array($filePath, $filePathList)) {
                $filePathList[$className] = $filePath;
            }
        } else {
            $filePathList[$className] = $filePath;
        }
        $fileData = json_encode($filePathList);
        $fileAssist->write($dirPathFile, $fileData);
    }

    private function readPath($className) {
        $dirPathFile = RZ_SDK_DIR_FILE_PATH;
        $fileAssist = new FileAssist();
        $fileData = json_encode(array());
        if(file_exists($dirPathFile)) {
            $fileData = $fileAssist->read($dirPathFile);
        }
        $filePathList = json_decode($fileData, true);
        if(!empty($filePathList)) {
            if (array_key_exists($className, $filePathList)) {
                $filePath = $filePathList[$className];
                if(file_exists($filePath)) {
                    require_once($filePath);
                    return true;
                }
            }
        }
        return false;
    }
}

Autoloader::autoload();