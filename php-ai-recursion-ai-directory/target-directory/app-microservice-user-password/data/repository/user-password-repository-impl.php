<?php
namespace App\Microservice\Data\Repository\User\Password;
?>
<?php
use Core\Database;
use Data\Models\UserPasswordModel;
use Domain\Entities\UserPasswordEntity;
use App\Microservice\Domain\Repository\User\Password\UserPasswordRepository;
use App\Microservice\Data\DataSources\User\Password\UserPasswordDataSource;
use PDO;

class UserPasswordRepositoryImpl implements UserPasswordRepository {
    private UserPasswordDataSource $dataSource;
    private PDO $db;

    public function __construct(UserPasswordDataSource $dataSource) {
        $this->dataSource = $dataSource;
        $this->db = Database::getInstance();
    }

    public function create(UserPasswordEntity $userPassword): UserPasswordEntity
    {
        $stmt = $this->db->prepare("
            INSERT INTO tbl_user_password (
                user_id, id, hash_type, password_salt, password_hash, 
                expiry, status, modified_date, created_date, modified_by, created_by
            ) VALUES (
                :user_id, :id, :hash_type, :password_salt, :password_hash, 
                :expiry, :status, NOW(), NOW(), :modified_by, :created_by
            )
        ");

        $stmt->execute([
            ':user_id' => $userPassword->user_id,
            ':id' => $userPassword->id,
            ':hash_type' => $userPassword->hash_type,
            ':password_salt' => $userPassword->password_salt,
            ':password_hash' => $userPassword->password_hash,
            ':expiry' => $userPassword->expiry,
            ':status' => $userPassword->status,
            ':modified_by' => $userPassword->modified_by,
            ':created_by' => $userPassword->created_by
        ]);

        return $userPassword;
    }

    public function getById(string $id): ?UserPasswordEntity
    {
        $stmt = $this->db->prepare("SELECT * FROM tbl_user_password WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$data) {
            return null;
        }

        return $this->mapToEntity($data);
    }

    public function getByUserId(string $userId): ?UserPasswordEntity
    {
        $stmt = $this->db->prepare("SELECT * FROM tbl_user_password WHERE user_id = :user_id ORDER BY created_date DESC LIMIT 1");
        $stmt->execute([':user_id' => $userId]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$data) {
            return null;
        }

        return $this->mapToEntity($data);
    }

    public function update(UserPasswordEntity $userPassword): bool
    {
        $stmt = $this->db->prepare("
            UPDATE tbl_user_password SET
                hash_type = :hash_type,
                password_salt = :password_salt,
                password_hash = :password_hash,
                expiry = :expiry,
                status = :status,
                modified_date = NOW(),
                modified_by = :modified_by
            WHERE id = :id
        ");

        return $stmt->execute([
            ':id' => $userPassword->id,
            ':hash_type' => $userPassword->hash_type,
            ':password_salt' => $userPassword->password_salt,
            ':password_hash' => $userPassword->password_hash,
            ':expiry' => $userPassword->expiry,
            ':status' => $userPassword->status,
            ':modified_by' => $userPassword->modified_by
        ]);
    }

    private function mapToEntity(array $data): UserPasswordEntity
    {
        $entity = new UserPasswordEntity();
        $entity->user_id = $data['user_id'];
        $entity->id = $data['id'];
        $entity->hash_type = $data['hash_type'];
        $entity->password_salt = $data['password_salt'];
        $entity->password_hash = $data['password_hash'];
        $entity->expiry = $data['expiry'];
        $entity->status = $data['status'];
        $entity->modified_date = $data['modified_date'];
        $entity->created_date = $data['created_date'];
        $entity->modified_by = $data['modified_by'];
        $entity->created_by = $data['created_by'];

        return $entity;
    }
}
?>