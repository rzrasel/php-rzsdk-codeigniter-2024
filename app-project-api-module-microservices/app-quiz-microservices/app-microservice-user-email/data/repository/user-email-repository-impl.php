<?php
namespace App\Microservice\Data\Repository\User\Email;
?>
<?php
use App\Microservice\Domain\Repository\User\Email\UserEmailRepository;
use App\Microservice\Schema\Domain\Model\User\Email\UserEmailEntity;
use App\Microservice\Core\Utils\Database\Database;
use App\Microservice\Core\Utils\Data\Response\ResponseData;
use App\Microservice\Core\Utils\Type\Response\ResponseStatus;
?>
<?php
class UserEmailRepositoryImpl implements UserEmailRepository {
    private $dbConn;
    public static $useEmailTableName = "tbl_user_email";

    public function __construct() {
        $this->dbConn = (new Database())->getConnection();
    }

    public function create(UserEmailEntity $userEmailEntity): ResponseData {
        return new ResponseData("User email created successfully.", ResponseStatus::SUCCESS, $userEmailEntity);
    }

    /*public function create(UserEmailModel $email): UserEmailModel {
        $stmt = $this->connection->prepare("
            INSERT INTO tbl_user_email (
                user_id, id, email, provider, is_primary, verification_code,
                last_verification_sent_at, verification_code_expiry, verification_status, status,
                created_date, modified_date, created_by, modified_by
            ) VALUES (
                :user_id, :id, :email, :provider, :is_primary, :verification_code,
                :last_verification_sent_at, :verification_code_expiry, :verification_status, :status,
                :created_date, :modified_date, :created_by, :modified_by
            )
        ");

        $stmt->execute([
            ':user_id' => $email->user_id,
            ':id' => $email->id,
            ':email' => $email->email,
            ':provider' => $email->provider,
            ':is_primary' => $email->is_primary,
            ':verification_code' => $email->verification_code,
            ':last_verification_sent_at' => $email->last_verification_sent_at,
            ':verification_code_expiry' => $email->verification_code_expiry,
            ':verification_status' => $email->verification_status,
            ':status' => $email->status,
            ':created_date' => $email->created_date,
            ':modified_date' => $email->modified_date,
            ':created_by' => $email->created_by,
            ':modified_by' => $email->modified_by
        ]);

        return $email;
    }

    public function findByEmail(string $email): ?UserEmailModel {
        $stmt = $this->connection->prepare("
            SELECT * FROM tbl_user_email 
            WHERE email = :email AND status != 'deleted'
        ");

        $stmt->execute([':email' => $email]);
        return $this->hydrate($stmt->fetch(PDO::FETCH_ASSOC));
    }

    public function findById(string $id): ?UserEmailModel {
        $stmt = $this->connection->prepare("
            SELECT * FROM tbl_user_email 
            WHERE id = :id AND status != 'deleted'
        ");

        $stmt->execute([':id' => $id]);
        return $this->hydrate($stmt->fetch(PDO::FETCH_ASSOC));
    }

    public function findByUserId(string $user_id): array {
        $stmt = $this->connection->prepare("
            SELECT * FROM tbl_user_email 
            WHERE user_id = :user_id AND status != 'deleted'
            ORDER BY is_primary DESC, created_date ASC
        ");

        $stmt->execute([':user_id' => $user_id]);
        $emails = [];

        while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
            if ($email = $this->hydrate($data)) {
                $emails[] = $email;
            }
        }

        return $emails;
    }

    public function findPrimaryByUserId(string $user_id): ?UserEmailModel {
        $stmt = $this->connection->prepare("
            SELECT * FROM tbl_user_email 
            WHERE user_id = :user_id AND is_primary = TRUE AND status != 'deleted'
            LIMIT 1
        ");

        $stmt->execute([':user_id' => $user_id]);
        return $this->hydrate($stmt->fetch(PDO::FETCH_ASSOC));
    }

    public function updateVerificationStatus(string $id, string $status): bool {
        $stmt = $this->connection->prepare("
            UPDATE tbl_user_email 
            SET verification_status = :status, 
                modified_date = :modified_date
            WHERE id = :id
        ");

        return $stmt->execute([
            ':id' => $id,
            ':status' => $status,
            ':modified_date' => date('Y-m-d H:i:s')
        ]);
    }

    public function setPrimary(string $user_id, string $id): bool {
        // First unset all primary emails
        $this->unsetPrimaryEmails($user_id);

        // Then set the new primary email
        $stmt = $this->connection->prepare("
            UPDATE tbl_user_email 
            SET is_primary = TRUE, 
                modified_date = :modified_date
            WHERE id = :id AND user_id = :user_id
        ");

        return $stmt->execute([
            ':id' => $id,
            ':user_id' => $user_id,
            ':modified_date' => date('Y-m-d H:i:s')
        ]);
    }

    public function remove(string $id): bool {
        $stmt = $this->connection->prepare("
            UPDATE tbl_user_email 
            SET status = 'deleted', 
                modified_date = :modified_date
            WHERE id = :id
        ");

        return $stmt->execute([
            ':id' => $id,
            ':modified_date' => date('Y-m-d H:i:s')
        ]);
    }

    public function setVerificationCode(string $id, string $code, string $expiry): bool {
        $stmt = $this->connection->prepare("
            UPDATE tbl_user_email 
            SET verification_code = :code,
                verification_code_expiry = :expiry,
                last_verification_sent_at = :sent_at,
                modified_date = :modified_date
            WHERE id = :id
        ");

        return $stmt->execute([
            ':id' => $id,
            ':code' => $code,
            ':expiry' => $expiry,
            ':sent_at' => date('Y-m-d H:i:s'),
            ':modified_date' => date('Y-m-d H:i:s')
        ]);
    }

    private function unsetPrimaryEmails(string $user_id): void {
        $stmt = $this->connection->prepare("
            UPDATE tbl_user_email 
            SET is_primary = FALSE, modified_date = :modified_date
            WHERE user_id = :user_id AND is_primary = TRUE
        ");

        $stmt->execute([
            ':user_id' => $user_id,
            ':modified_date' => date('Y-m-d H:i:s')
        ]);
    }

    private function hydrate(?array $data): ?UserEmailModel {
        if (!$data) return null;

        $email = new UserEmailModel(
            $data['user_id'],
            $data['email'],
            $data['created_by'],
            $data['modified_by'],
            $data['provider'],
            $data['is_primary'],
            $data['verification_status'],
            $data['status'],
            $data['id']
        );

        $email->verification_code = $data['verification_code'];
        $email->last_verification_sent_at = $data['last_verification_sent_at'];
        $email->verification_code_expiry = $data['verification_code_expiry'];
        $email->created_date = $data['created_date'];
        $email->modified_date = $data['modified_date'];

        return $email;
    }*/
}
?>