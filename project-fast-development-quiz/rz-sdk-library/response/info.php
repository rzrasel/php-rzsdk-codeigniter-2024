<?php
namespace RzSDK\Response;
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
?>
