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
        $pageName = array("Page Name:" => "User Authentication Token");
        $responseMessage = array_merge($pageName, $responseMessage);
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
            "user_auth_token"   => "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ0b2tlbl9zdGFydF90aW1lIjoxNzIwNzY4MTk3LCJkZXZpY2VfdHlwZSI6ImFuZHJvaWQiLCJ0b2tlbl9zdGFydF9kYXRlIjoiMjAyNC0wNy0xMiAwOTowOTo1NyIsInVzZXJfdG9rZW5fa2V5IjoidWllYXMiLCJ1c2VyX3Rva2VuX2lkIjoiMTcyMDc2ODE5NzQzNTYzMjMxbDE3MjA3NjgxOTczNTIxMjc5MGwxNzIxMzcyOTk3bDE3MjA3NjgxOTc0MzQ5ODAxMmwxNzIwNzY4MTk3bDUyMzMiLCJhZ2VudF90eXBlIjoiYW5kcm9pZF9hcHAiLCJ0b2tlbl9yZWZyZXNoX2RhdGUiOiIyMDI0LTA3LTEyIDA5OjA5OjU3IiwidG9rZW5fZXhwaXJ5X2RhdGUiOiIyMDI0LTA3LTE5IDA5OjA5OjU3IiwidG9rZW5fZXhwaXJ5X3RpbWUiOjE3MjEzNzI5OTcsInVzZXJfaXAiOiIxMjcuMC4wLjEiLCJ0b2tlbl9yZWZyZXNoX3RpbWUiOjE3MjA3NjgxOTd9.8dC80IDySF8lrG08bAZWdvPeoAQ4r70kzyURaXYqvdw"
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