CREATE TABLE IF NOT EXISTS tbl_user_password (
user_id                       VARCHAR(36)      NOT NULL,
id                            VARCHAR(36)      NOT NULL,
hash_type                     TEXT             NOT NULL DEFAULT 'password_hash' CHECK(hash_type IN ('password_hash', 'SHA256', 'bcrypt', 'argon2')),
password_salt                 TEXT             NULL,
password_hash                 TEXT             NOT NULL,
expiry                        TIMESTAMP        NULL,
status                        TEXT             NOT NULL DEFAULT 'active' CHECK(status IN ('active', 'inactive', 'expired')),
modified_date                 TIMESTAMP        NOT NULL DEFAULT CURRENT_TIMESTAMP,
created_date                  TIMESTAMP        NOT NULL DEFAULT CURRENT_TIMESTAMP,
modified_by                   VARCHAR(36)      NOT NULL,
created_by                    VARCHAR(36)      NOT NULL,
CONSTRAINT pk_user_password_id PRIMARY KEY(id),
CONSTRAINT fk_user_password_user_id_user_data_user_id FOREIGN KEY(user_id) REFERENCES tbl_user_data(user_id)
);



//----- File: app-microservice-core/base-request.php -----

<?php
namespace Core\Data\Request;

abstract class BaseRequest {

    abstract public function rules(): array;

    public function authorize(): bool {
        return true;
    }

    public function validationData(): array {
        return (array) $this;
    }

    public function __get(string $name) {
        if (property_exists($this, $name)) {
            return $this->{$name};
        }
        return null;
    }

    public function __set(string $name, $value): void {
        $this->{$name} = $value;
    }

    public function __call(string $method, array $parameters) {
        throw new \BadMethodCallException(sprintf(
            'Method %s::%s does not exist.', static::class, $method
        ));
    }
}
?>

//----- File: app-microservice-core/database-type.php -----

<?php
namespace App\Microservice\Core\Utils\Type\Database;
?>
<?php
enum DatabaseType: string {
    case MYSQL = "mysql";
    case SQLITE = "sqlite";

    public static function getByName(string $value): ?self {
        foreach (self::cases() as $case) {
            if ($case->name === $value) {
                return $case;
            }
        }
        return null;
    }

    public static function getByValue(string $value): ?self {
        foreach (self::cases() as $case) {
            if ($case->value === $value) {
                return $case;
            }
        }
        return null;
    }
}
?>

//----- File: app-microservice-core/database.php -----

<?php
namespace App\Microservice\Core\Utils\Database;
?>
<?php
use App\Microservice\Core\Utils\Type\Database\DatabaseType;
use RzSDK\Database\SqliteConnection;
?>
<?php
class Database {
    //private SqliteConnection $dbConn;
    private $dbConn;
    //
    public function __construct() {
        $databaseType = DATABASE_TYPE;
        switch($databaseType) {
            case DatabaseType::MYSQL:
                break;
            case DatabaseType::SQLITE:
                $this->dbConn = $this->getSqliteDbConn();
                break;
            default:
                $this->dbConn = $this->getSqliteDbConn();
        }
    }

    public function getConnection() {
        return $this->dbConn;
    }

    private function getSqliteDbConn(): SqliteConnection {
        if(is_null($this->dbConn)) {
            return SqliteConnection::getInstance(DB_FULL_PATH);
        }
        return $this->dbConn;
    }
}
?>

//----- File: app-microservice-core/inner-data-bus.php -----

<?php
namespace App\Microservice\Core\Utils\Data\Inner\Data\Bus;
?>
<?php
use App\Microservice\Core\Utils\Type\Response\ResponseStatus;
?>
<?php
class InnerDataBus {
    public string $message;
    public bool $status;
    public $data;
    public ?ResponseStatus $type;

    public function __construct(string $message, bool $status, $data = null, ResponseStatus $type = null) {
        $this->message = $message;
        $this->status = $status;
        $this->data = $data;
        $this->type = $type;
    }
}
?>


//----- File: app-microservice-core/response-data.php -----

<?php
namespace App\Microservice\Core\Utils\Data\Response;
?>
<?php
use App\Microservice\Core\Utils\Type\Response\ResponseStatus;
?>
<?php
class ResponseData {
    public $message;
    public $status;
    public $data;
    public int $status_code;

    public function __construct(string $message, ResponseStatus $status, mixed $data = null, int $statusCode = 200) {
        $this->message = $message;
        $this->status = $status->value;
        $this->data = $data;
        $this->status_code = $statusCode;
    }

    public function toJson(): string {
        return json_encode($this);
    }
}
?>

//----- File: app-microservice-core/response-status.php -----

<?php
namespace App\Microservice\Core\Utils\Type\Response;
?>
<?php
enum ResponseStatus: string {
    case SUCCESS    = "success";
    case ERROR      = "error";

    public static function getByName(string $value): ?self {
        foreach (self::cases() as $case) {
            if ($case->name === $value) {
                return $case;
            }
        }
        return null;
    }

    public static function getByValue(string $value): ?self {
        foreach (self::cases() as $case) {
            if ($case->value === $value) {
                return $case;
            }
        }
        return null;
    }
}
?>

//----- File: app-microservice-user-password/data/data-source/user-password-data-source.php -----

<?php
namespace App\Microservice\Data\DataSources\User\Password;
?>
<?php
use Core\Database;
use App\Microservice\Schema\Data\Model\User\Password\UserPasswordModel;
use PDO;

class UserPasswordDataSource {
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

//----- File: app-microservice-user-password/data/model/user-password-model.php -----

<?php
namespace App\Microservice\Schema\Data\Model\User\Password;
?>
<?php
class UserPasswordModel {
    public string $user_id;
    public string $id;
    public string $hash_type;
    public ?string $password_salt;
    public string $password_hash;
    public ?string $expiry;
    public string $status;
    public string $modified_date;
    public string $created_date;
    public string $modified_by;
    public string $created_by;
}
?>

//----- File: app-microservice-user-password/data/repository/user-password-repository-impl.php -----

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
    private PDO $db;

    public function __construct(UserPasswordDataSource $dataSource) {
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

//----- File: app-microservice-user-password/domain/model/user-password-entity.php -----

<?php
namespace App\Microservice\Schema\Domain\Model\User\Password;
?>
<?php
class UserPasswordEntity {
    public string $user_id;
    public string $id;
    public string $hash_type;
    public ?string $password_salt;
    public string $password_hash;
    public ?string $expiry;
    public string $status;
    public string $modified_date;
    public string $created_date;
    public string $modified_by;
    public string $created_by;

    public function toArray(): array {
        return [
            'user_id' => $this->user_id,
            'id' => $this->id,
            'hash_type' => $this->hash_type,
            'password_salt' => $this->password_salt,
            'password_hash' => $this->password_hash,
            'expiry' => $this->expiry,
            'status' => $this->status,
            'modified_date' => $this->modified_date,
            'created_date' => $this->created_date,
            'modified_by' => $this->modified_by,
            'created_by' => $this->created_by
        ];
    }
}
?>

//----- File: app-microservice-user-password/domain/repository/user-password-repository.php -----

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

//----- File: app-microservice-user-password/domain/use-case/user-password-use-case.php -----

<?php
namespace App\Microservice\Domain\UseCase\User\Password;
?>
<?php
use Core\ResponseData;
use Domain\Entities\UserPasswordEntity;
use App\Microservice\Domain\Repository\User\Password\UserPasswordRepository;

class UserPasswordUseCase {
    private UserPasswordRepository $repository;

    public function __construct(UserPasswordRepository $repository) {
        $this->repository = $repository;
    }

    public function createPassword(UserPasswordEntity $userPassword): ResponseData {
        try {
            $createdPassword = $this->repository->create($userPassword);
            return new ResponseData(true, 'Password created successfully', $createdPassword->toArray(), 201);
        } catch (\Exception $e) {
            return new ResponseData(false, 'Failed to create password: ' . $e->getMessage(), null, 500);
        }
    }

    /*public function getPasswordById(string $id): ResponseData {
        try {
            $password = $this->repository->getById($id);
            if (!$password) {
                return new ResponseData(false, 'Password not found', null, 404);
            }
            return new ResponseData(true, 'Password retrieved successfully', $password->toArray());
        } catch (\Exception $e) {
            return new ResponseData(false, 'Failed to retrieve password: ' . $e->getMessage(), null, 500);
        }
    }

    public function getPasswordByUserId(string $userId): ResponseData {
        try {
            $password = $this->repository->getByUserId($userId);
            if (!$password) {
                return new ResponseData(false, 'Password not found for this user', null, 404);
            }
            return new ResponseData(true, 'Password retrieved successfully', $password->toArray());
        } catch (\Exception $e) {
            return new ResponseData(false, 'Failed to retrieve password: ' . $e->getMessage(), null, 500);
        }
    }

    public function updatePassword(UserPasswordEntity $userPassword): ResponseData {
        try {
            $success = $this->repository->update($userPassword);
            if (!$success) {
                return new ResponseData(false, 'Password update failed', null, 400);
            }
            return new ResponseData(true, 'Password updated successfully');
        } catch (\Exception $e) {
            return new ResponseData(false, 'Failed to update password: ' . $e->getMessage(), null, 500);
        }
    }*/
}
?>

//----- File: app-microservice-user-password/index.php -----

<?php
header("Content-Type: application/json");
?>
<?php
require_once("include.php");
?>
<?php
use App\Microservice\Core\Utils\Data\Response\ResponseData;
use App\Microservice\Core\Utils\Type\Response\ResponseStatus;
use App\Microservice\Presentation\Controller\Use\Password\UserPasswordController;
?>
<?php
//header("Content-Type: application/json");
if ($_SERVER['REQUEST_METHOD'] !== "POST") {
    http_response_code(200);
    $responseData = new ResponseData("Only POST method is allowed", ResponseStatus::ERROR, null);
    echo $responseData->toJson();
    exit;
}
?>
<?php
if(empty($_POST)) {
    $rawData = file_get_contents("php://input");
    $inputData = json_decode($rawData, true);
    if(!empty($inputData)) {
        $_POST = $inputData;
    }
}
$controller = new UserPasswordController();
$response = $controller->addEmail($_POST);
echo $response->toJson();
?>

//----- File: app-microservice-user-password/presentation/user-password-controller.php -----

<?php
namespace App\Microservice\Presentation\Controller\Use\Password;
?>
<?php
use App\Microservice\Data\Repository\User\Password\UserPasswordRepositoryImpl;
use App\Microservice\Domain\UseCase\User\Password\UserPasswordUseCase;
use App\Microservice\Core\Utils\Data\Response\ResponseData;
?>
<?php
class UserPasswordController {
    private UserPasswordUseCase $useCase;

    public function __construct() {
        $repository = new UserPasswordRepositoryImpl();
        $this->useCase = new UserPasswordUseCase($repository);
    }

    public function create(array $input): ResponseData {
        // Validate input
        $requiredFields = ['user_id', 'hash_type', 'password_hash', 'modified_by', 'created_by'];
        foreach ($requiredFields as $field) {
            if (empty($input[$field])) {
                return new ResponseData(false, "Missing required field: $field", null, 400);
            }
        }

        $userPassword = new UserPasswordEntity();
        $userPassword->user_id = $input['user_id'];
        $userPassword->id = $input['id'] ?? uniqid();
        $userPassword->hash_type = $input['hash_type'];
        $userPassword->password_salt = $input['password_salt'] ?? null;
        $userPassword->password_hash = $input['password_hash'];
        $userPassword->expiry = $input['expiry'] ?? null;
        $userPassword->status = $input['status'] ?? 'active';
        $userPassword->modified_by = $input['modified_by'];
        $userPassword->created_by = $input['created_by'];

        return $this->useCase->createPassword($userPassword);
    }

    public function getById(string $id): ResponseData {
        return $this->useCase->getPasswordById($id);
    }

    public function getByUserId(string $userId): ResponseData {
        return $this->useCase->getPasswordByUserId($userId);
    }

    public function update(array $input): ResponseData {
        // Validate input
        $requiredFields = ['id', 'hash_type', 'password_hash', 'modified_by'];
        foreach ($requiredFields as $field) {
            if (empty($input[$field])) {
                return new ResponseData(false, "Missing required field: $field", null, 400);
            }
        }

        $userPassword = new UserPasswordEntity();
        $userPassword->id = $input['id'];
        $userPassword->hash_type = $input['hash_type'];
        $userPassword->password_salt = $input['password_salt'] ?? null;
        $userPassword->password_hash = $input['password_hash'];
        $userPassword->expiry = $input['expiry'] ?? null;
        $userPassword->status = $input['status'] ?? 'active';
        $userPassword->modified_by = $input['modified_by'];

        return $this->useCase->updatePassword($userPassword);
    }
}
?>


//----- File: app-microservice-user-password/presentation/user-password-view-model.php -----

<?php
namespace App\Microservice\Presentation\ViewModel\Use\Password;
?>
<?php
use App\Microservice\Core\Utils\Data\Response\ResponseData;
use App\Microservice\Domain\UseCase\User\Password\UserPasswordUseCase;
?>
<?php
class UserPasswordViewModel {

    public function __construct(UserPasswordUseCase $useCase) {}

    public static function createResponse(ResponseData $response): string {
        http_response_code($response->statusCode);
        header('Content-Type: application/json');
        return $response->toJson();
    }

    public static function formatPasswordData(array $passwordData): array {
        return [
            'user_id' => $passwordData['user_id'],
            'id' => $passwordData['id'],
            'hash_type' => $passwordData['hash_type'],
            'status' => $passwordData['status'],
            'expiry' => $passwordData['expiry'],
            'created_date' => $passwordData['created_date'],
            'modified_date' => $passwordData['modified_date']
        ];
    }
}
?>





- mvvm clean architecture directory structure
- provide directory file name location top of codebase
- mvvm clean architecture user password in php api only
- mvvm clean architecture data layer, domain layer, presentation layer
- mvvm clean architecture different user input request data layer, database insert data layer, response data layer
- use response for ResponseData class
- also provide how to use usage of UserPasswordController class or call UserPasswordController class

*** refactor and optimize code if needed
*** don't break code consistency
*** must be code consistency
*** code or directory refactor if needed
*** code or directory refactor if better options
*** Don't change class name if not needed
*** Don't change unwanted codebase
*** provide full code
*** Provide Full Codebase
*** read full document properly