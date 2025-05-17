<?php
namespace App\Microservice\Data\Data\Source\User\Mobile;
?>
<?php

use App\Microservice\Core\Utils\Data\Response\ResponseData;
use App\Microservice\Data\Data\Access\Object\User\Mobile\UserMobileLocalDAO;
use App\Microservice\Data\Data\Access\Object\User\Mobile\UserMobileRemoteDAO;
use App\Microservice\Schema\Data\Model\Entity\User\Mobile\UserMobileEntity;

?>
<?php
class UserMobileDataSourceImpl implements UserMobileDataSource {
    private UserMobileLocalDAO $localDao;
    private UserMobileRemoteDAO $remoteDao;

    public function __construct(UserMobileLocalDAO $localDao, UserMobileRemoteDAO $remoteDao) {
        $this->localDao = $localDao;
        $this->remoteDao = $remoteDao;
    }

    public function create(UserMobileEntity $userMobile): ResponseData {
        // TODO: Implement create() method.
    }

    public function update(UserMobileEntity $userMobile): ResponseData {
        // TODO: Implement update() method.
    }

    public function select(UserMobileEntity $userMobile, array $columns): ResponseData {
        // TODO: Implement select() method.
    }
}
?>
