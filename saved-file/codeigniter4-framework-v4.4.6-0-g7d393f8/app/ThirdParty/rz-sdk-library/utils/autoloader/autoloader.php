<?php
namespace RzSDK\Autoloader;
// defined("RZ_SDK_BASEPATH") or define("RZ_SDK_BASEPATH", trim(trim(__DIR__, "/")));
defined("RZ_SDK_BASEPATH") OR exit("No direct script access allowed");
defined("RZ_SDK_WRAPPER") OR exit("No direct script access allowed");
require_once(RZ_SDK_WRAPPER . "/utils/autoloader/autoloader-helper.php");
use RzSDK\Autoloader\AutoloaderHelper;

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
        $filePath = parent::adaptFileName($className);
        parent::prepareExistedFile(RZ_SDK_WRAPPER, $this->dirList, $filePath, ".php");
        /* echo "<pre>";
        print_r($filePath);
        echo "</pre>";
        print($helper->getExistedFile()); */
        if(file_exists(parent::getExistedFile())) {
            require_once(parent::getExistedFile());
        }
    }
}

Autoloader::autoload();