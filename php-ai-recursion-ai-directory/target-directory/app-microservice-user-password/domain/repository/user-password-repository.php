<?php
namespace App\Microservice\Domain\Repository\User\Password;
?>
<?php
interface UserPasswordRepository {
    public function create(UserPasswordEntity $userPassword): UserPasswordEntity;
    public function getById(string $id): ?UserPasswordEntity;
    public function getByUserId(string $userId): ?UserPasswordEntity;
    public function update(UserPasswordEntity $userPassword): bool;
}
?>