<?php
namespace RzSDK\User\Login;
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
            "user_auth_token"   => "7U0z5q3dETxkHoMNIxRA+SFycB+fZ9lKsRb5KM2xYnev8xiv4X94aZZpelUDlUkeTzUU6pv1E99+F9c9rMit/ddb8yBjkN1SbsKmVSAwFvOGYnA/6GN61SFgzCZhO6otwpyMtIx1txH1QIE4XF9+idYsT7XFKBJ8f6CWIyMJv0+yQlNNs2wiYs5q9c+VoitQ/akY4jrTy+FBE4RFxW/xMSkdfMcwn6pVvUBNm/mgjqwKIA7JpZthLqXxdkR+263EA2myTrPPt0Qr1up+wpQzEuAWvTSYmvsa77SziilL9hETjTfSlOpEipL+6FCmmI+ILBQMzBNy6urMQjvW2z0pbr8Gr2qfRXFElcDEP8LMqpaZIjxjJJ7ilMNPTBEVb0HrHn1hoNxhLLhAhPsQxq3hQ7avRPQbZSzaKVcUQPMhvssTI5GxG1d9vjj45wpmQjkr1u+osXqTxhO38/RrnefW4J4/MKMIhwkjkHoE+wXcQYVsSO97Tv+drYkU4M+ORF60"
        );
    }

    private function getErrorResponse() {
        return array(
            "body" => null,
            "info" => array(
                "message" => "Error inform to developer",
                "type" => "error",
            ),
            "parameter" => $this->getData(),
        );
    }
}
?>