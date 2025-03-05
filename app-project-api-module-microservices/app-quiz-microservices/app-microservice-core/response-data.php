<?php
namespace App\Microservice\Core\Utils\Data\Response;
?>
<?php
use App\Microservice\Core\Utils\Type\Response\ResponseType;
?>
<?php
class ResponseData {
    public $message;
    public $type;
    public $data;

    public function __construct(string $message, ResponseType $type, $data = null) {
        $this->message = $message;
        $this->type = $type->value;
        $this->data = $data;
    }

    public function toJson(): string {
        return json_encode($this);
    }
}
?>