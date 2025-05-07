<?php
namespace App\Microservice\Data\DataSources\User\Password;
?>
<?php
use App\Microservice\Schema\Domain\Model\User\Password\UserPasswordEntity;
use App\Microservice\Core\Utils\Data\Response\ResponseData;
?>
<?php
interface UserPasswordDataSource {
    public function create(UserPasswordEntity $userPassword): ResponseData;
    public function update(UserPasswordEntity $userPassword): ResponseData;
    public function select(UserPasswordEntity $userPassword, array $columns): ResponseData;
    public function isActivePasswordExists(UserPasswordEntity $userPassword): ResponseData;
    /*public function getById(string $id): ?UserPasswordEntity;
    public function getByUserId(string $userId): ?UserPasswordEntity;*/
}
?>