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
    public function isPasswordExists(UserPasswordEntity $userPassword): bool;
    /*public function getById(string $id): ?UserPasswordEntity;
    public function getByUserId(string $userId): ?UserPasswordEntity;
    public function update(UserPasswordEntity $userPassword): bool;*/
}
?>