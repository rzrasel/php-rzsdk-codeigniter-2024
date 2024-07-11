<?php
namespace RzSDK\User\Authentication\Token;
?>
<?php
use RzSDK\Curl\Curl;
use RzSDK\Log\DebugLog;
?>
<?php
class CurlUserAuthTokenAuthentication {
    private $url;
    private $path = "/user-auth-token-authentication/user-auth-token-authentication.php";

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
        /*if(!is_array($result)) {
            return;
        }*/
        unset($result["info"]);
        unset($result["error"]);
        DebugLog::log($result);
        if(!is_array($result)) {
            $this->printResponse();
            return;
        }
        if(array_key_exists("body", $result)) {
            $responseData = json_decode($result["body"], true);
            //DebugLog::log($responseData);
            (!empty($responseData)) ? $this->printResponse($responseData) : $this->printResponse();
        } else {
            $this->printResponse();
        }
        //DebugLog::log($responseData);
    }

    private function printResponse(array $responseMessage = array()) {
        if(empty($responseMessage)) {
            $responseMessage = $this->getErrorResponse();
        }
        $responseData = json_encode($responseMessage);
        DebugLog::log($responseData);
    }

    private function getData() {
        return array(
            "device_type"       => "android",
            "auth_type"         => "email",
            "agent_type"        => "android_app",
            "user_email"        => "email@gmail.com",
            "password"          => "123456aB#",
            "user_auth_token"   => "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VyX3Rva2VuX2lkIjoiMTcyMDcyNzkzNzYxOTU3NjYxbDE3MjEzMzI3MzdsMTcyMDcyNzkzNzYxOTYzNTY4bDE3MjA3Mjc5MzdsMTcyMDcyNzkzNzUzNzMzMTEzbDYzMDAiLCJ1c2VyX2lwIjoiMTI3LjAuMC4xIiwidG9rZW5fc3RhcnRfZGF0ZSI6IjIwMjQtMDctMTEgMjE6NTg6NTciLCJ0b2tlbl9zdGFydF90aW1lIjoxNzIwNzI3OTM3LCJ0b2tlbl9leHBpcnlfZGF0ZSI6IjIwMjQtMDctMTggMjE6NTg6NTciLCJ0b2tlbl9leHBpcnlfdGltZSI6MTcyMTMzMjczNywidG9rZW5fcmVmcmVzaF9kYXRlIjoiMjAyNC0wNy0xMSAyMTo1ODo1NyIsInRva2VuX3JlZnJlc2hfdGltZSI6MTcyMDcyNzkzNywidXNlcl90b2tlbl9rZXkiOiJ1ZWFzaSJ9.YmL5UasV2ORDYy2Hwt5aoKp833mk-UtrXeylUPYDlkY"
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
?>