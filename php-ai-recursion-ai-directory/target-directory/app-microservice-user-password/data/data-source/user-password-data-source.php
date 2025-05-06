<?php
namespace App\Microservice\Data\DataSources\User\Password;
?>
<?php
use App\Microservice\Schema\Data\Model\User\Password\UserPasswordModel;
use App\Microservice\Schema\Domain\Model\User\Password\UserPasswordEntity;

interface UserPasswordDataSource {
    public function create(UserPasswordEntity $userPassword): UserPasswordEntity;
    public function getById(string $id): ?UserPasswordEntity;
    public function getByUserId(string $userId): ?UserPasswordEntity;
    public function update(UserPasswordEntity $userPassword): bool;
}
?>