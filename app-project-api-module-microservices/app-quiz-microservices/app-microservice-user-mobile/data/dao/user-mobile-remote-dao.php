<?php
namespace App\Microservice\Data\Data\Access\Object\User\Mobile;
?>
<?php
?>
<?php
class UserMobileRemoteDAO {
    private array $db = [];

    public function insert(UserEntity $user): bool {
        $this->db[$user->id] = $user;
        return true;
    }

    public function findById(string $id): ?UserEntity {
        return $this->db[$id] ?? null;
    }
}
?>