Database Schema

CREATE TABLE IF NOT EXISTS tbl_user_mobile (
user_id                       VARCHAR(36)      NOT NULL,
id                            VARCHAR(36)      NOT NULL,
mobile                        VARCHAR(20)      NOT NULL,
country_code                  VARCHAR(5)       NULL,
provider                      VARCHAR(255)     NOT NULL DEFAULT 'user' CHECK(provider IN ('user', 'google', 'facebook')),
is_primary                    BOOLEAN          NOT NULL DEFAULT FALSE,
verification_code             VARCHAR(8)       NULL,
last_verification_sent_at     TIMESTAMP        NULL,
verification_code_expiry      TIMESTAMP        NULL,
verification_status           TEXT             NOT NULL DEFAULT 'pending' CHECK(verification_status IN ('pending', 'verified', 'expired', 'blocked')),
status                        TEXT             NOT NULL DEFAULT 'active' CHECK(status IN ('active', 'inactive', 'blocked', 'deleted', 'removed')),
modified_date                 TIMESTAMP        NOT NULL DEFAULT CURRENT_TIMESTAMP,
created_date                  TIMESTAMP        NOT NULL DEFAULT CURRENT_TIMESTAMP,
modified_by                   VARCHAR(36)      NOT NULL,
created_by                    VARCHAR(36)      NOT NULL,
CONSTRAINT pk_user_mobile_id PRIMARY KEY(id),
CONSTRAINT uk_user_mobile_mobile UNIQUE(mobile),
CONSTRAINT fk_user_mobile_user_id_user_data_user_id FOREIGN KEY(user_id) REFERENCES tbl_user_data(user_id)
);


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
    public string $message;
    public mixed $status;
    public int $status_code;
    public mixed $data;
    public int $line;

    public function __construct(
        string $message,
        mixed $status,
        mixed $data = null,
        int $statusCode = 200,
        int $line = 0,
    ) {
        $this->message = $message;
        if($status instanceof ResponseStatus) {
            $this->status = $status->value;
        } else {
            $this->status = $status;
        }
        $this->data = $data;
        $this->status_code = $statusCode;
        $this->line = $line;
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

//----- File: app-microservice-core/universal-vault.php -----

<?php
namespace App\Microservice\Core\Utils\Universal\Vault;
?>
<?php
?>
<?php
class UniversalVault {}
?>

//----- File: app-microservice-user-mobile/data/dao/user-mobile-local-dao.php -----

<?php
namespace App\Microservice\Data\Data\Access\Object\User\Mobile;
?>
<?php
use App\Microservice\Schema\Data\Model\Entity\User\Mobile\UserMobileEntity;
use App\Microservice\Core\Utils\Data\Response\ResponseData;
?>
<?php
class UserMobileLocalDAO {
    private array $db = [];

    public function insert(UserMobileEntity $user): ResponseData {
        $this->db[$user->id] = $user;
        return true;
    }

    public function findById(string $id): ResponseData {
        return $this->db[$id];
    }
}
?>

//----- File: app-microservice-user-mobile/data/dao/user-mobile-remote-dao.php -----

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

//----- File: app-microservice-user-mobile/data/datasource/user-mobile-data-source-impl.php -----

<?php
namespace App\Microservice\Data\Data\Source\User\Mobile;
?>
<?php

use App\Microservice\Core\Utils\Data\Response\ResponseData;
use App\Microservice\Data\Data\Access\Object\User\Mobile\UserMobileLocalDAO;
use App\Microservice\Data\Data\Access\Object\User\Mobile\UserMobileRemoteDAO;
use App\Microservice\Schema\Data\Model\Entity\User\Mobile\UserMobileEntity;

?>
<?php
class UserMobileDataSourceImpl implements UserMobileDataSource {
    private UserMobileLocalDAO $localDao;
    private UserMobileRemoteDAO $remoteDao;

    public function __construct(UserMobileLocalDAO $localDao, UserMobileRemoteDAO $remoteDao) {
        $this->localDao = $localDao;
        $this->remoteDao = $remoteDao;
    }

    public function create(UserMobileEntity $userMobile): ResponseData {
        // TODO: Implement create() method.
    }

    public function update(UserMobileEntity $userMobile): ResponseData {
        // TODO: Implement update() method.
    }

    public function select(UserMobileEntity $userMobile, array $columns): ResponseData {
        // TODO: Implement select() method.
    }
}
?>


//----- File: app-microservice-user-mobile/data/datasource/user-mobile-data-source.php -----

<?php
namespace App\Microservice\Data\Data\Source\User\Mobile;
?>
<?php
use App\Microservice\Core\Utils\Data\Response\ResponseData;
use App\Microservice\Schema\Data\Model\Entity\User\Mobile\UserMobileEntity;
?>
<?php
interface UserMobileDataSource {
    public function create(UserMobileEntity $userMobile): ResponseData;
    public function update(UserMobileEntity $userMobile): ResponseData;
    public function select(UserMobileEntity $userMobile, array $columns): ResponseData;
}
?>


//----- File: app-microservice-user-mobile/data/mapper/user-mobile-mapper.php -----

<?php
namespace App\Microservice\Data\Model\Mapper\User\Mobile;
?>
<?php
?>
<?php
class UserMobileMapper {
}
?>


//----- File: app-microservice-user-mobile/data/model/entity/user-mobile-entity.php -----

<?php
namespace App\Microservice\Schema\Data\Model\Entity\User\Mobile;
?>
<?php
class UserMobileEntity {
    public string $id;
    public string $name;
    public string $email;

    public function __construct(string $id, string $name, string $email) {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
    }

    public function getVarList() {
        $result = array_intersect_key(
            get_object_vars($this),
            get_mangled_object_vars($this)
        );
        return array_keys($result);
    }

    public function getVarListWithKey() {
        return array_intersect_key(
            get_object_vars($this),
            get_mangled_object_vars($this)
        );
    }
}
?>

//----- File: app-microservice-user-mobile/data/repository/user-mobile-repository-impl.php -----

<?php
namespace App\Microservice\Data\Repository\User\Mobile;
?>
<?php
use App\Microservice\Domain\Repository\User\Mobile\UserMobileRepository;
use App\Microservice\Core\Utils\Data\Response\ResponseData;
use App\Microservice\Schema\Domain\Model\User\Model\UserMobileModel;
use App\Microservice\Data\Data\Source\User\Mobile\UserMobileDataSource;
?>
<?php
class UserMobileRepositoryImpl implements UserMobileRepository {
    private UserMobileDataSource $dataSource;

    public function __construct(UserMobileDataSource $dataSource) {
        $this->dataSource = $dataSource;
    }

    public function create(UserMobileModel $userEmail): ResponseData {
        // TODO: Implement create() method.
    }

    public function update(UserMobileModel $userMobile): ResponseData {
        // TODO: Implement update() method.
    }

    public function select(UserMobileModel $userEmail, array $columns): ResponseData {
        // TODO: Implement select() method.
    }
}
?>


//----- File: app-microservice-user-mobile/di/user-mobile-module.php -----

<?php
namespace App\Microservice\Dependency\Injection\Module\Use\Mobile;
?>
<?php
use App\Microservice\Data\Data\Access\Object\User\Mobile\UserMobileLocalDAO;
use App\Microservice\Data\Data\Access\Object\User\Mobile\UserMobileRemoteDAO;
use App\Microservice\Data\Data\Source\User\Mobile\UserMobileDataSource;
use App\Microservice\Data\Data\Source\User\Mobile\UserMobileDataSourceImpl;
use App\Microservice\Domain\Repository\User\Mobile\UserMobileRepository;
use App\Microservice\Data\Repository\User\Mobile\UserMobileRepositoryImpl;
use App\Microservice\Domain\Usecase\User\Mobile\UserMobileUseCase;
use App\Microservice\Presentation\ViewModel\Use\Mobile\UserMobileViewModel;
?>
<?php
class UserMobileModule {

    private function provideLocalDAO(): UserMobileLocalDAO {
        return new UserMobileLocalDAO();
    }

    private function provideRemoteDAO(): UserMobileRemoteDAO {
        return new UserMobileRemoteDAO();
    }

    private function provideDataSource(UserMobileLocalDAO $localDao, UserMobileRemoteDAO $remoteDao): UserMobileDataSource {
        return new UserMobileDataSourceImpl($localDao, $remoteDao);
    }

    private function provideRepository(UserMobileDataSource $dataSource): UserMobileRepository {
        return new UserMobileRepositoryImpl($dataSource);
    }

    private function provideUseCase(UserMobileRepository $repository): UserMobileUseCase {
        return new UserMobileUseCase($repository);
    }

    public function provideViewModel(): UserMobileViewModel {
        $localDao = $this->provideLocalDAO();
        $remoteDao = $this->provideRemoteDAO();
        $dataSource = $this->provideDataSource($localDao, $remoteDao);
        $repository = $this->provideRepository($dataSource);
        $useCase = $this->provideUseCase($repository);
        return new UserMobileViewModel($useCase);
    }
}
?>


//----- File: app-microservice-user-mobile/domain/model/user-mobile-model.php -----

<?php
namespace App\Microservice\Schema\Domain\Model\User\Model;
?>
<?php
class UserMobileModel {
    public string $id;
    public string $name;
    public string $email;

    public function __construct(string $id, string $name, string $email) {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
    }

    public function getVarList() {
        $result = array_intersect_key(
            get_object_vars($this),
            get_mangled_object_vars($this)
        );
        return array_keys($result);
    }

    public function getVarListWithKey() {
        return array_intersect_key(
            get_object_vars($this),
            get_mangled_object_vars($this)
        );
    }
}
?>


//----- File: app-microservice-user-mobile/domain/repository/user-mobile-repository.php -----

<?php
namespace App\Microservice\Domain\Repository\User\Mobile;
?>
<?php
use App\Microservice\Schema\Domain\Model\User\Model\UserMobileModel;
use App\Microservice\Core\Utils\Data\Response\ResponseData;
?>
<?php
interface UserMobileRepository {
    public function create(UserMobileModel $userEmail): ResponseData;
    public function update(UserMobileModel $userMobile): ResponseData;
    public function select(UserMobileModel $userEmail, array $columns): ResponseData;
}
?>


//----- File: app-microservice-user-mobile/domain/usecase/user-mobile-use-case.php -----

<?php
namespace App\Microservice\Domain\Usecase\User\Mobile;
?>
<?php
use App\Microservice\Core\Utils\Data\Response\ResponseData;
use App\Microservice\Domain\Repository\User\Mobile\UserMobileRepository;
?>
<?php
class UserMobileUseCase {
    private UserMobileRepository $repository;

    public function __construct(UserMobileRepository $repository) {
        $this->repository = $repository;
    }

    public function createMobile(UserMobileRequestModel $request): ResponseData {
        // TODO: Implement createMobile() method.
    }

    private function updateMobile(UserMobileRequestModel $request): array {
        // TODO: Implement selectMobile() method.
    }

    public function selectMobile($columns): ResponseData {
        // TODO: Implement selectMobile() method.
    }
}
?>


//----- File: app-microservice-user-mobile/presentation/user-mobile-controller.php -----

<?php
namespace App\Microservice\Presentation\Controller\Use\Mobile;
?>
<?php
use App\Microservice\Core\Utils\Data\Response\ResponseData;
use App\Microservice\Core\Utils\Type\Response\ResponseStatus;
use App\Microservice\Dependency\Injection\Module\Use\Mobile\UserMobileModule;
use App\Microservice\Presentation\ViewModel\Use\Mobile\UserMobileViewModel;
?>
<?php
class UserMobileController {
    private UserMobileViewModel $viewModel;
    public function __construct() {
        $this->viewModel = (new UserMobileModule())->provideViewModel();
    }

    public function executeController(array $request): ResponseData {
        try {
            return $this->viewModel->executeViewModel($request);
        } catch (\Throwable $e) {
            return new ResponseData(
                "An error occurred: " . $e->getMessage(),
                ResponseStatus::ERROR,
                $request,
                500,
                __LINE__
            );
        }
    }
}
?>


//----- File: app-microservice-user-mobile/presentation/user-mobile-view-model.php -----

<?php
namespace App\Microservice\Presentation\ViewModel\Use\Mobile;
?>
<?php
use App\Microservice\Core\Utils\Data\Response\ResponseData;
use App\Microservice\Schema\Data\Model\User\Email\UserEmailInsertRequestModel;
use App\Microservice\Type\Action\Email\EmailActionType;
use App\Microservice\Core\Utils\Type\Response\ResponseStatus;
use App\Microservice\Schema\Data\Model\User\Email\UserEmailSelectRequestModel;
use App\Microservice\Domain\Usecase\User\Mobile\UserMobileUseCase;
?>
<?php
class UserMobileViewModel {
    private $useCase;

    public function __construct(UserMobileUseCase $useCase) {
        $this->useCase = $useCase;
    }

    public function executeViewModel(array $request): ResponseData {
        if (empty($request['action_type'])) {
            return new ResponseData(
                "Missing action_type",
                ResponseStatus::ERROR,
                null,
                400
            );
        }

        $actionType = EmailActionType::getByValue($request['action_type']);
        if (!$actionType) {
            return new ResponseData(
                "Invalid action_type",
                ResponseStatus::ERROR,
                null,
                400
            );
        }

        return match ($actionType) {
            EmailActionType::INSERT => $this->createEmail($request),
            EmailActionType::SELECT => $this->selectEmail($request),
            default => new ResponseData(
                "Unsupported action type",
                ResponseStatus::ERROR,
                null,
                400
            )
        };
    }

    private function createEmail(array $request): ResponseData {
        $required = array(
            "user_id",
            "action_type",
        );
        foreach ($required as $field) {
            if (empty($request[$field])) {
                return new ResponseData(
                    "Missing required field: $field",
                    ResponseStatus::ERROR,
                    null,
                    400
                );
            }
        }

        $model = new UserEmailInsertRequestModel();
        $this->mapRequestToModel($request, $model);
        return $this->useCase->createEmail($model);
    }

    private function selectEmail(array $request): ResponseData {
        $model = new UserEmailSelectRequestModel();
        $this->mapRequestToModel($request, $model);

        if (isset($request['columns'])) {
            $model->columns = $request['columns'];
        }

        return $this->useCase->selectEmail($model);
    }

    private function mapRequestToModel(array $request, object $model): void {
        foreach ($model->getVarList() as $property) {
            if (array_key_exists($property, $request)) {
                $model->{$property} = $request[$property];
            }
        }
    }
}
?>





PHP Simple Proper MVVM Clean Architecture-

- mvvm clean architecture directory structure
- provide directory file name location for request data model, response data model and database data model
- database both like remote database and local database
- use dao data access object
- where is place request data model and response data model in data or domain layer
- request data model for user input, request data model for remote request
- response data model for user response, request data model for remote response
- difference between domain UserMobileModel and data UserMobileEntity, usage of them
- provide PHP simple proper mvvm clean architecture using - request model and response model - user input, request model for remote request - user response, request model for remote response - UserMobileModel UserMobileEntity
- use UserMobileUseCase, UserMobileRepository, UserMobileDataSource, data mapper class
- use UserModule for dependency injection
- controller access UserMobileViewModel
- if you want you can use DAO for maintain remote and local database
- clearly separation use of UserModel and UserEntity
- also show use of database like insert, update, retrieve data from local and remote database proper use of UserMobileModel and UserMobileEntity properly maintain data layer structure
- return back UserMobileModel and UserEntity properly maintain data layer structure
- properly place model and all class file in proper location
- perspective of PHP mvvm clean architecture
- provide full codebase in php



- mvvm clean architecture directory structure
- provide directory file name location for request data model, response data model and database data model
- database both like remote database and local database
- use dao data access object
- where is place request data model and response data model in data or domain layer
- request data model for user input, request data model for remote request
- response data model for user response, request data model for remote response
- perspective of PHP mvvm clean architecture

- difference between domain User and data UserEntity, usage of them