<?php
namespace RzSDK\User\Login;
?>
<?php
use RzSDK\Curl\Curl;
use RzSDK\Log\DebugLog;
?>
<?php
class CurlUserLogin {
    private $url;
    private $path = "/user-login/user-login.php";

    public function __construct($url) {
        $this->url = $url;
        /* DebugLog::log("hi CurlUserLogin");
        new Test(); */
        $this->execute();
    }

    private function execute() {
        $url = $this->url . $this->path;
        $curl = new Curl($url);
        $result = $curl->exec(true, $this->getData()) . "";
        $result = json_decode($result, true);
        //DebugLog::log($result);
        unset($result["info"]);
        unset($result["error"]);
        DebugLog::log($result);
        /* $responseData = json_decode($result["body"], true);
        DebugLog::log($responseData); */
    }

    private function getData() {
        return array(
            "device_type"   => "android",
            "auth_type"     => "email",
            "agent_type"    => "android_app",
            "user_email"    => "email@gmail.com",
            "password"      => "123456aB#",
        );
    }
}
class Test {
    public function __construct() {
        DebugLog::log("hi Test");
    }
}
?>