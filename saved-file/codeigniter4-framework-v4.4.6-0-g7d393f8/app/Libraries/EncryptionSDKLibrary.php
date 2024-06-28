<?php
namespace App\Libraries;
use RzSDK\Autoloader\RunAutoloader;

class EncryptionSDKLibrary {
    function __construct() {
        require_once(APPPATH . "ThirdParty/rz-sdk-library/run-autoloader.php");
        $template = new RunAutoloader(FCPATH);
        /* echo APPPATH;
        echo "<br />"; */
        //echo "Libraries " . __CLASS__ . " ---- " . __METHOD__;
        //$template = new PasswordEncryption();
    }
}