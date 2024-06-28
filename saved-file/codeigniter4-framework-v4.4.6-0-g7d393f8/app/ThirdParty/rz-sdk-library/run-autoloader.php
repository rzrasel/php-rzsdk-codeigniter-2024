<?php
namespace RzSDK\Autoloader;
// defined("RZ_SDK_BASEPATH") or define("RZ_SDK_BASEPATH", trim(trim(__DIR__, "/")));
// require_once(dirname(RZ_SDK_BASEPATH) . "/utils/autoloader/autoloader.php");
defined("RZ_SDK_BASEPATH") or define("RZ_SDK_BASEPATH", trim(trim(__DIR__, "/")));
use RzSDK\Autoloader\Autoloader;

if(version_compare(PHP_VERSION, "5.4.0", "<")) {
    exit("The Rz SDK requires PHP version 5.4 or higher.");
}

class RunAutoloader {
    private const dirList = array(
        "",
        "utils/autoloader",
        "utils/date-time",
        "utils/encryption",
        "utils/database",
        "user/signup",
        "user/signin",
    );
    public function __construct($rootPath) {
        $rootPath = trim(trim($rootPath, "/"));
        $rzSdkFolder = str_replace($rootPath, "", RZ_SDK_BASEPATH);
        $rzSdkFolder = trim(trim($rzSdkFolder, "/"));
        defined("RZ_SDK_WRAPPER") or define("RZ_SDK_WRAPPER", str_replace("\\", "/", $rzSdkFolder));

        /* echo $rootPath;
        echo "<br />";
        echo RZ_SDK_BASEPATH;
        echo "<br />";
        echo RZ_SDK_WRAPPER;
        echo "<br />"; */

        require_once(RZ_SDK_WRAPPER . "/utils/autoloader/autoloader.php");
        //global $autoloader;
        $autoloader = new Autoloader($rootPath, self::dirList);
    }
}