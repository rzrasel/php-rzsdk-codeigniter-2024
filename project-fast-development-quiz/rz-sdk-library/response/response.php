<?php
namespace RzSDK\Response;
?>
<?php
class Response {
    public $body;
    public Info $info;
    public $parameter;

    public function __construct($body = null, Info $info = new Info(null, InfoType::MESSAGE), $parameter = null) {
        $this->body = $body;
        $this->info = $info;
        $this->parameter = $parameter;
    }

    public function toJson() {
        return json_encode($this);
    }
}
?>