<?php
namespace App\Microservice\Utils\Language\Data\Request;
?>
<?php
class RequestData {
    public $databaseType;
    public $data;

    public function __construct(string $databaseType, $data = null) {
        $this->databaseType = $databaseType;
        $this->data = $data;
    }
}
?>