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
            "user_auth_token"   => "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VyX3Rva2VuX2lkIjoiMTcyMDUyMjYwNjUxMTI5MDE5fDE3MjA2MzI1MDQyNDA0NTYzNHwxNzIxMjM3MzA0fDE3MjA2MzI1MDR8MTcyMDYzMjUwNDI0MDE2OTA0fDk2MTAiLCJ1c2VyX2lwIjoiMTI3LjAuMC4xIiwidG9rZW5fc3RhcnRfZGF0ZSI6IjIwMjQtMDctMTAgMTk6Mjg6MjQiLCJ0b2tlbl9zdGFydF90aW1lIjoxNzIwNjMyNTA0LCJ0b2tlbl9leHBpcnlfZGF0ZSI6IjIwMjQtMDctMTcgMTk6Mjg6MjQiLCJ0b2tlbl9leHBpcnlfdGltZSI6MTcyMTIzNzMwNCwidG9rZW5fcmVmcmVzaF9kYXRlIjoiMjAyNC0wNy0xMCAxOToyODoyNCIsInRva2VuX3JlZnJlc2hfdGltZSI6MTcyMDYzMjUwNCwidXNlcl90b2tlbl9rZXkiOiJpYWVzdSJ9.eSgzEbyw0b7jQooEenoDXRWA31_c89Wzj-vJH_5XRn4"
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