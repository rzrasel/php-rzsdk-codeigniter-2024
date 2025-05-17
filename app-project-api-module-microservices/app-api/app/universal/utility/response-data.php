<?php
namespace App\Core\Universal\Utils\Data\Response;
?>
<?php
use App\Core\Universal\Utils\Data\Response\ResponseStatus;
?>
<?php
class ResponseData {
    public string $message;
    public mixed $status;
    public int $status_code;
    public mixed $data;
    public int $line;

    public function __construct(
        string $message,
        mixed $status,
        mixed $data = null,
        int $statusCode = 200,
        int $line = 0,
    ) {
        $this->message = $message;
        if($status instanceof ResponseStatus) {
            $this->status = $status->value;
        } else {
            $this->status = $status;
        }
        $this->data = $data;
        $this->status_code = $statusCode;
        $this->line = $line;
    }

    public function toJson(): string {
        return json_encode($this);
    }
}
?>