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
        if(!is_array($result)) {
            $this->printResponse();
            return;
        }
        if(array_key_exists("body", $result)) {
            $responseData = json_decode($result["body"], true);
            (!empty($responseData)) ? $this->printResponse($responseData) : $this->printResponse();
        } else {
            $this->printResponse();
        }
        /* $responseData = json_decode($result["body"], true);
        DebugLog::log($responseData); */
    }

    private function printResponse(array $responseMessage = array()) {
        if(empty($responseMessage)) {
            $responseMessage = $this->getErrorResponse();
        }
        $pageName = array("Page Name:" => "User Login");
        $responseMessage = array_merge($pageName, $responseMessage);
        $responseData = json_encode($responseMessage);
        DebugLog::log($responseData);
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

    private function getErrorResponse() {
        return array(
            "body" => null,
            "info" => array(
                "message" => "Error inform to developer, JSON data error",
                "type" => "error",
            ),
            "parameter" => $this->getData(),
        );
    }
}
class Test {
    public function __construct() {
        DebugLog::log("hi Test");
    }
}
?>