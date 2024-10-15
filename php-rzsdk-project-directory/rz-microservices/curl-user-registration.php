<?php
namespace RzSDK\User\Registration;
?>
<?php
use RzSDK\Curl\Curl;
use RzSDK\DatabaseSpace\UserRegistrationTable;
use RzSDK\Log\DebugLog;
?>
<?php
class CurlUserRegistration {
    public $test = 9;
    private $url;
    private $path = "/user-registration/user-registration.php";
    public $testTest = 9;

    public function __construct($url) {
        $this->url = $url;
        $this->execute();
        //$this->example();
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
        $pageName = array("Page Name:" => "User Registration");
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

    public function example() {
        //DebugLog::log(get_object_vars($this));
        //DebugLog::log(get_mangled_object_vars($this));
        /* $result = array_intersect_key(get_object_vars($this), get_mangled_object_vars($this));
        DebugLog::log($result); */
        //$class_vars = get_class_vars(get_class($my_class));
        $userRegistration = new UserRegistrationTable();
        DebugLog::log($userRegistration->getColumn());
        DebugLog::log($userRegistration->getColumnWithKey());
        /* $inTest = $userRegistration->test;
        DebugLog::log($userRegistration->$inTest());
        DebugLog::log($userRegistration->{$userRegistration->test}()); */
    }
}
?>