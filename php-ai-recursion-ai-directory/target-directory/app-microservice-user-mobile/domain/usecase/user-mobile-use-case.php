<?php
namespace App\Microservice\Domain\Usecase\User\Mobile;
?>
<?php
use App\Microservice\Core\Utils\Data\Response\ResponseData;
use App\Microservice\Domain\Repository\User\Mobile\UserMobileRepository;
?>
<?php
class UserMobileUseCase {
    private UserMobileRepository $repository;

    public function __construct(UserMobileRepository $repository) {
        $this->repository = $repository;
    }

    public function createMobile(UserMobileRequestModel $request): ResponseData {
        // TODO: Implement createMobile() method.
    }

    private function updateMobile(UserMobileRequestModel $request): array {
        // TODO: Implement selectMobile() method.
    }

    public function selectMobile($columns): ResponseData {
        // TODO: Implement selectMobile() method.
    }
}
?>
