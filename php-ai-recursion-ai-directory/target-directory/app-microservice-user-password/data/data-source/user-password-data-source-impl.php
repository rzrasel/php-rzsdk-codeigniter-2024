<?php
namespace App\Microservice\Data\DataSources\User\Password;
?>
<?php
use Core\Database;
use App\Microservice\Schema\Data\Model\User\Password\UserPasswordModel;
use PDO;
use App\Microservice\Data\DataSources\User\Password\UserPasswordDataSource;
?>
<?php
class UserPasswordDataSourceImpl implements UserPasswordDataSource {
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function create(UserPasswordModel $model): bool
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

        return $stmt->execute([
            ':user_id' => $model->user_id,
            ':id' => $model->id,
            ':hash_type' => $model->hash_type,
            ':password_salt' => $model->password_salt,
            ':password_hash' => $model->password_hash,
            ':expiry' => $model->expiry,
            ':status' => $model->status,
            ':modified_by' => $model->modified_by,
            ':created_by' => $model->created_by
        ]);
    }

    public function getById(string $id): ?UserPasswordModel
    {
        $stmt = $this->db->prepare("SELECT * FROM tbl_user_password WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$data) {
            return null;
        }

        $model = new UserPasswordModel();
        $model->user_id = $data['user_id'];
        $model->id = $data['id'];
        $model->hash_type = $data['hash_type'];
        $model->password_salt = $data['password_salt'];
        $model->password_hash = $data['password_hash'];
        $model->expiry = $data['expiry'];
        $model->status = $data['status'];
        $model->modified_date = $data['modified_date'];
        $model->created_date = $data['created_date'];
        $model->modified_by = $data['modified_by'];
        $model->created_by = $data['created_by'];

        return $model;
    }
}
?>