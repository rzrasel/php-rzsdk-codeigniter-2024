<?php
namespace App\Microservice\Core\Utils\Data\Response;
?>
<?php
use App\Microservice\Core\Utils\Type\Response\ResponseStatus;
?>
<?php
class ResponseData {
    public $message;
    public $status;
    public $data;

    public function __construct(string $message, ResponseStatus $status, mixed $data = null) {
        $this->message = $message;
        $this->status = $status->value;
        $this->data = $data;
    }

    public function toJson(): string {
        return json_encode($this);
    }
}
?>