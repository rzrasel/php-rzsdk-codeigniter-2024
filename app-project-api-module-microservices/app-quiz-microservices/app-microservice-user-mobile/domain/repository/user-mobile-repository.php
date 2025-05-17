<?php
namespace App\Microservice\Domain\Repository\User\Mobile;
?>
<?php
use App\Microservice\Schema\Domain\Model\User\Model\UserMobileModel;
use App\Microservice\Core\Utils\Data\Response\ResponseData;
?>
<?php
interface UserMobileRepository {
    public function create(UserMobileModel $userEmail): ResponseData;
    public function update(UserMobileModel $userMobile): ResponseData;
    public function select(UserMobileModel $userEmail, array $columns): ResponseData;
}
?>
