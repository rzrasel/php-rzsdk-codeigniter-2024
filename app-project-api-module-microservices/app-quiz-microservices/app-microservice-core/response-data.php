<?php
namespace App\Microservice\Core\Utils\Data\Response;
?>
<?php
use App\Microservice\Core\Utils\Type\Response\ResponseStatus;
?>
<?php
class ResponseData {
    public string $message;
    public mixed $status;
    public int $status_code;
    public mixed $data;

    public function __construct(
        string $message,
        mixed $status,
        mixed $data = null,
        int $statusCode = 200
    ) {
        $this->message = $message;
        if($status instanceof ResponseStatus) {
            $this->status = $status->value;
        } else {
            $this->status = $status;
        }
        $this->data = $data;
        $this->status_code = $statusCode;
    }

    public function toJson(): string {
        return json_encode($this);
    }
}
?>