<?php
namespace App\Libraries;

class TestSDKLibrary {
    function __construct() {
        require_once(APPPATH . "ThirdParty/test-sdk-library/test-sdk-library.php");
        //echo "Libraries " . __CLASS__ . " ---- " . __METHOD__;
    }
}