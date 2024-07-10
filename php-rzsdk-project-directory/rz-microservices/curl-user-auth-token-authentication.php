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
            "user_auth_token"   => "NmHabCBaIO+9t1adC2KPQd/ABxn9e7yRAyo0rzfbqTNNCrPsTHoaMbz3ADrtqZeaWsXWnXvjYSAcxyvVSdSgF5BVB2uFS8GyKVpekU8BFAciVFYH8ojv0S8tvJCHRUr8xYFxbVgU0oBQTKf7/poYoc5aXLwW8V5sBoxbF+4yb+yTpMGgimkBeLCI2GuieXJHkre7MOVyAXaFP/L9vixMgKi87SsKuSbgi898+rUb0h/Y74u6otTigmvzqG+jRt/vPEQO/mwO3Sxzy7VLXNvLqGH3pecjWJ1UtsL/ZnWv0k6sHKmsK1mQ8sFct6DbZzW2ltYbnvQp+mzYPeqSDNSo23Lw/gwiBwYsAC1vP3DmeQ94UXJqsTq8hUsTV0ftpw44ARmaJltzB2z1IMMPpcS8Mm4fdpSzruyKQYXQ3Bak8odNu/GMDzf/mKXWezkTaj3+kica52MK91dA38Is3Nu/nbHI/XQ8uAfc2Yt1ACacuDelJPzO1QVtJMsUtIZahPA6"
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