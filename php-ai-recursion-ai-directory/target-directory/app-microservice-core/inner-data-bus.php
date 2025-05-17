<?php
namespace App\Microservice\Core\Utils\Data\Inner\Data\Bus;
?>
<?php
use App\Microservice\Core\Utils\Type\Response\ResponseStatus;
?>
<?php
class InnerDataBus {
    public string $message;
    public bool $status;
    public $data;
    public ?ResponseStatus $type;

    public function __construct(string $message, bool $status, $data = null, ResponseStatus $type = null) {
        $this->message = $message;
        $this->status = $status;
        $this->data = $data;
        $this->type = $type;
    }
}
?>
