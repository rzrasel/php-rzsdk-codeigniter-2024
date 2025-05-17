<?php
namespace App\Microservice\Data\Repository\User\Mobile;
?>
<?php
use App\Microservice\Domain\Repository\User\Mobile\UserMobileRepository;
use App\Microservice\Core\Utils\Data\Response\ResponseData;
use App\Microservice\Schema\Domain\Model\User\Model\UserMobileModel;
use App\Microservice\Data\Data\Source\User\Mobile\UserMobileDataSource;
?>
<?php
class UserMobileRepositoryImpl implements UserMobileRepository {
    private UserMobileDataSource $dataSource;

    public function __construct(UserMobileDataSource $dataSource) {
        $this->dataSource = $dataSource;
    }

    public function create(UserMobileModel $userEmail): ResponseData {
        // TODO: Implement create() method.
    }

    public function update(UserMobileModel $userMobile): ResponseData {
        // TODO: Implement update() method.
    }

    public function select(UserMobileModel $userEmail, array $columns): ResponseData {
        // TODO: Implement select() method.
    }
}
?>
