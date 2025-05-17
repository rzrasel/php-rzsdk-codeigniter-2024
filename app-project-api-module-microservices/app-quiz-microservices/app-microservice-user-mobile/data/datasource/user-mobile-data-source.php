<?php
namespace App\Microservice\Data\Data\Source\User\Mobile;
?>
<?php
use App\Microservice\Core\Utils\Data\Response\ResponseData;
use App\Microservice\Schema\Data\Model\Entity\User\Mobile\UserMobileEntity;
?>
<?php
interface UserMobileDataSource {
    public function create(UserMobileEntity $userMobile): ResponseData;
    public function update(UserMobileEntity $userMobile): ResponseData;
    public function select(UserMobileEntity $userMobile, array $columns): ResponseData;
}
?>
