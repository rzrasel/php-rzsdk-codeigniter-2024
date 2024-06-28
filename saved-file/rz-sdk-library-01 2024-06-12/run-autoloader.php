<?php
namespace RzSDK\Autoloader;
// defined("RZ_SDK_BASEPATH") or define("RZ_SDK_BASEPATH", trim(trim(__DIR__, "/")));
// require_once(dirname(RZ_SDK_BASEPATH) . "/utils/autoloader/autoloader.php");
defined("RZ_SDK_BASEPATH") or define("RZ_SDK_BASEPATH", trim(trim(__DIR__, "/")));
defined("RZ_SDK_DIR_FILE_PATH") or define("RZ_SDK_DIR_FILE_PATH", "directory-path-file.json");
?>
<?php
use RzSDK\Autoloader\Autoloader;
?>
<?php
if(version_compare(PHP_VERSION, "5.4.0", "<")) {
    exit("The Rz SDK requires PHP version 5.4 or higher.");
}

class RunAutoloader {
    /* private const dirList = array(
        "",
        "utils/autoloader",
        "utils/date-time",
        "utils/encryption",
        "utils/database",
        "user/signup",
        "user/signin",
    ); */
    private const dirList = array(
        "",
        "autoloader",
        "date-time",
        "encryption",
        "database",
        //"signup",
        //"signin",
    );
    public function __construct($rootPath) {
        $rootPath = trim(trim($rootPath, "/"));
        $rzSdkFolder = $rootPath . "/rz-sdk-library";
        /* $rzSdkFolder = str_replace($rootPath, "", RZ_SDK_BASEPATH);
        $rzSdkFolder = trim(trim($rzSdkFolder, "/")); */
        defined("RZ_SDK_WRAPPER") or define("RZ_SDK_WRAPPER", str_replace("\\", "/", $rzSdkFolder));

        /* echo $rootPath;
        echo "<br />";
        echo RZ_SDK_BASEPATH;
        echo "<br />";
        echo RZ_SDK_WRAPPER;
        echo "<br />"; */

        $filePath = RZ_SDK_WRAPPER . "/autoloader/autoloader.php";
        //require_once(RZ_SDK_WRAPPER . "/rz-sdk-library/autoloader/autoloader.php");
        require_once($filePath);
        //global $autoloader;
        $autoloader = new Autoloader($rootPath, self::dirList);
        /* $dirPathFile = RZ_SDK_DIR_FILE_PATH;
        $fileWriter = new FileAssist();
        $fileWriter->write($dirPathFile, "data"); */
    }
}
new RunAutoloader(trim(trim(__DIR__, "/")));