<?php
namespace App\Microservice\Presentation\Controller\Use\Email;
?>
<?php
use App\Microservice\Core\Utils\Data\Response\ResponseData;
use App\Microservice\Schema\Data\Services\User\Email\UserEmailService;
?>
<?php
class UserEmailViewModel {
    private $service;

    public function __construct(UserEmailService $service) {
        $this->service = $service;
    }

    public function addEmail($requestDataSet): ResponseData {
        return $this->service->addEmail($requestDataSet);
    }
}
?>