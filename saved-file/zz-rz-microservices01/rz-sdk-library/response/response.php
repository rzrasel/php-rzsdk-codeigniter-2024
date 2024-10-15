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
<?php
class Info {
    public $message;
    public $type;

    public function __construct($message, InfoType $infoType) {
        $this->message = $message;
        $this->type = $infoType->value;
    }
}
enum InfoType: string {
    case ALERT      = "alert";
    case ERROR      = "error";
    case INFO       = "info";
    case MESSAGE    = "message";
    case SUCCESS    = "success";
    case WARNING    = "warning";
    case NONE       = "none";
}
?>
<?php
//https://github.com/php/php-src/issues/9352
//Retrieving an enum case by its name
function getInfoTypeByValue($value) {
    foreach (InfoType::cases() as $case) {
        /* if ($case->name === $enumName) {
            return $case;
        } */
        if ($case->value === $value) {
            return $case;
        }
    }
    return null;
}
?>