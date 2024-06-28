<?php
namespace RzSDK\Encryption\Autoloader;
// defined("RZ_SDK_BASEPATH") or define("RZ_SDK_BASEPATH", trim(trim(__DIR__, "/")));
defined("RZ_SDK_BASEPATH") OR exit("No direct script access allowed");
defined("RZ_SDK_WRAPPER") OR exit("No direct script access allowed");
require_once(dirname(RZ_SDK_BASEPATH) . "/autoloader/autoloader_helper.php");
use RzSDK\Autoloader\AutoloaderHelper;

class Autoloader extends AutoloaderHelper {
    private function __construct() {
        //spl_autoload_register(array($this, "autoloadRegister"));
    }

    public static function autoload() {
        /* echo "Libraries: " . __CLASS__ . " ---- " . __METHOD__;
        echo "<br />";
        echo RZ_SDK_BASEPATH;
        echo "<br />"; */
        spl_autoload_register([__CLASS__, "autoloadRegister"]);
    }

    private static function autoloadRegister($className) {
        /* echo "Libraries: " . __CLASS__ . " ---- " . __METHOD__;
        echo "<br />"; */
        $helper = new AutoloaderHelper();
        $filePath = $helper->adaptFileName($className);
        $helper->prepareExistedFile(RZ_SDK_BASEPATH, "", $filePath, ".php");
        /* echo "<pre>";
        print_r($filePath);
        echo "</pre>";
        print($helper->getExistedFile()); */
        if(file_exists($helper->getExistedFile())) {
            require_once($helper->getExistedFile());
        }
    }
}

Autoloader::autoload();