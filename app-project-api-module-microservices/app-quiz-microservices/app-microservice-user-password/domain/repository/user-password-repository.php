<?php
namespace App\Microservice\Domain\Repository\User\Password;
?>
<?php
use App\Microservice\Schema\Data\Model\User\Password\UserPasswordModel;
use App\Microservice\Core\Utils\Data\Response\ResponseData;
?>
<?php
interface UserPasswordRepository {
    public function create(UserPasswordModel $userPassword): ResponseData;
    public function update(UserPasswordModel $userPassword): ResponseData;
    public function select(UserPasswordModel $userPassword, array $columns): ResponseData;
    /*public function getById(string $id): ?UserPasswordEntity;
    public function getByUserId(string $userId): ?UserPasswordEntity;
    public function update(UserPasswordEntity $userPassword): bool;*/
}
?>