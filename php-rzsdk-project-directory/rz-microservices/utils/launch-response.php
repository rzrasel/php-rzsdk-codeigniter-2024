<?php
namespace RzSDK\HTTPResponse;
?>
<?php
use RzSDK\Response\Response;
use RzSDK\Response\Info;
use RzSDK\Response\InfoType;
?>
<?php
class LaunchResponse {
    //
    private $body;
    private Info $info;
    private $parameter;
    //

    public function __construct() {}

    public function setBody($body = null) {
        $this->body = $body;
        return $this;
    }

    public function setInfo($message, InfoType $infoType) {
        $this->info = new Info($message, $infoType);
        return $this;
    }

    public function setParameter($parameter = null) {
        $this->parameter = $parameter;
        return $this;
    }

    public function execute($isPrint = true): string {
        //if(empty($this->body) || empty($this->info)) {
        if(empty($this->info)) {
            $this->body = null;
            $this->info = new Info("HTTP response info can not be null", InfoType::ERROR);
        }
        $response = new Response();
        $response->body         = $this->body;
        $response->info         = $this->info;
        $response->parameter    = $this->parameter;
        if($isPrint) {
            echo $response->toJson();
        }
        return $response->toJson();
    }
}
?>