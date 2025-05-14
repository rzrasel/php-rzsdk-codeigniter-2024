Database Schema:

CREATE TABLE IF NOT EXISTS tbl_user_email (
user_id                       VARCHAR(36)      NOT NULL,
id                            VARCHAR(36)      NOT NULL,
email                         VARCHAR(320)     NOT NULL,
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
CONSTRAINT pk_user_email_id PRIMARY KEY(id),
CONSTRAINT uk_user_email_email UNIQUE(email),
CONSTRAINT fk_user_email_user_id_user_data_user_id FOREIGN KEY(user_id) REFERENCES tbl_user_data(user_id)
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

    public function __construct(
        string $message,
        mixed $status,
        mixed $data = null,
        int $statusCode = 200
    ) {
        $this->message = $message;
        if($status instanceof ResponseStatus) {
            $this->status = $status->value;
        } else {
            $this->status = $status;
        }
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

//----- File: app-microservice-core/universal-vault.php -----

<?php
namespace App\Microservice\Core\Utils\Universal\Vault;
?>
<?php
?>
<?php
class UniversalVault {}
?>

//----- File: app-microservice-user-email/data/mapper/user-email-mapper.php -----

<?php
namespace App\Microservice\Data\Mapper\User\Email;
?>
<?php
use App\Microservice\Schema\Domain\Model\User\Email\UserEmailEntity;
use App\Microservice\Schema\Data\Model\User\Email\UserEmailModel;
use App\Microservice\Schema\Data\Model\User\Email\UserEmailResponseDto;
use RzSDK\Database\SqliteFetchType;
?>
<?php
class UserEmailMapper {
    public static function getDataVarList(UserEmailModel $requestDataModel) {
        return $requestDataModel->getVarList();
    }

    public static function getDomainVarList(UserEmailEntity $modelData) {
        return $modelData->getVarList();
    }

    public static function mapModelToEntity(UserEmailModel $dataModel): UserEmailEntity {
        $entityData = new UserEmailEntity();
        $dataVarList = $dataModel->getVarList();
        $domainVarList = $entityData->getVarList();
        array_map(function ($key) use ($dataModel, $domainVarList, $entityData) {
            if (in_array($key, $domainVarList)) {
                $entityData->{$key} = $dataModel->{$key};
            }
        }, $dataVarList);
        return $entityData;
    }

    public static function mapEntityToResponseDto(mixed $domainModel): mixed {
        if (is_array($domainModel)) {
            $retDataList = array();
            $userEmailResponseDto = new UserEmailResponseDto();
            $userEmailEntity = new UserEmailEntity();
            $dataVarList = $userEmailResponseDto->getVarList();
            $domainVarList = $userEmailEntity->getVarList();
            foreach($domainModel as $item) {
                $responseModel = new UserEmailResponseDto();
                foreach ($dataVarList as $key) {
                    if (in_array($key, $domainVarList)) {
                        $responseModel->{$key} = $item->{$key};
                    }
                }
                $responseModel->user_email = $item->email;
                $responseModel->email_provider = $item->provider;
                $retDataList[] = $responseModel;
            }
            return $retDataList;
        }
        $userEmailResponseDto = new UserEmailResponseDto();
        $dataVarList = $userEmailResponseDto->getVarList();
        $domainVarList = $domainModel->getVarList();
        foreach ($domainVarList as $key) {
            if (in_array($key, $dataVarList)) {
                $userEmailResponseDto->{$key} = $domainModel->{$key};
            }
        }
        $userEmailResponseDto->user_email = $domainModel->email;
        $userEmailResponseDto->email_provider = $domainModel->provider;
        return $userEmailResponseDto;
    }

    public static function mapDbToEntity($dbData): array {
        //echo gettype($dbData);
        //print_r($dbData);
        $retVal = array();
        $entityData = new UserEmailEntity();
        $dataVarList = $entityData->getVarList();
        if(is_object($dbData)) {
            while ($row = $dbData->fetch(SqliteFetchType::FETCH_OBJ->value)) {
                $entityData = new UserEmailEntity();
                foreach ($dataVarList as $key) {
                    $entityData->{$key} = $row->{$key};
                }
                $retVal[] = $entityData;
            }
            /*foreach ($dbData as $item) {
                //print_r($item);
                $entityData = new UserEmailEntity();
                foreach ($dataVarList as $key) {
                    $entityData->{$key} = $item[$key];
                }
                $retVal[] = $entityData;
            }*/
        } else if(is_array($dbData)) {
            foreach ($dbData as $item) {
                print_r($item);
                $entityData = new UserEmailEntity();
                /*foreach ($dataVarList as $key) {
                    $entityData->{$key} = $item[$key];
                }*/
                $retVal[] = $entityData;
            }
        }
        return $retVal;
    }

    /*public static function mapEntityToParams(UserEmailModel $email): array {
        return [
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
        ];
    }*/
}
?>

//----- File: app-microservice-user-email/data/model/user-email-insert-request-model.php -----

<?php
namespace App\Microservice\Schema\Data\Model\User\Email;
?>
<?php
class UserEmailInsertRequestModel {
    public $user_id;
    public $user_email;
    public $email_provider;
    public $is_primary;
    public $verification_code;
    public $action_type;

    public function __construct(
        $user_id = null,
        $user_email = null,
        $email_provider = null,
        $is_primary = null,
        $verification_code = null,
        $action_type = null,
    ) {
        $this->user_id = $user_id;
        $this->user_email = $user_email;
        $this->email_provider = $email_provider;
        $this->is_primary = $is_primary;
        $this->verification_code = $verification_code;
        $this->action_type = $action_type;
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

//----- File: app-microservice-user-email/data/model/user-email-model.php -----

<?php
namespace App\Microservice\Schema\Data\Model\User\Email;
?>
<?php
class UserEmailModel {
    public $user_id;
    public $id;
    public $email;
    public $provider;
    public $is_primary;
    public $verification_code;
    public $last_verification_sent_at;
    public $verification_code_expiry;
    public $verification_status;
    public $status;
    public $created_date;
    public $modified_date;
    public $created_by;
    public $modified_by;

    public function __construct(
        $user_id = null,
        $id = null,
        $email = null,
        $provider = null,
        $is_primary = null,
        $verification_code = null,
        $last_verification_sent_at = null,
        $verification_code_expiry = null,
        $verification_status = null,
        $status = null,
        $created_date = null,
        $modified_date = null,
        $created_by = null,
        $modified_by = null
    ) {
        $this->user_id = $user_id;
        $this->id = $id;
        $this->email = $email;
        $this->provider = $provider;
        $this->is_primary = $is_primary;
        $this->verification_code = $verification_code;
        $this->last_verification_sent_at = $last_verification_sent_at;
        $this->verification_code_expiry = $verification_code_expiry;
        $this->verification_status = $verification_status;
        $this->status = $status;
        $this->created_date = $created_date;
        $this->modified_date = $modified_date;
        $this->created_by = $created_by;
        $this->modified_by = $modified_by;
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

//----- File: app-microservice-user-email/data/model/user-email-response-dto.php -----

<?php
namespace App\Microservice\Schema\Data\Model\User\Email;
?>
<?php
class UserEmailResponseDto {
    public $user_id;
    public $id;
    public $user_email;
    public $email_provider;
    public $is_primary;
    public $last_verification_sent_at;
    public $verification_code_expiry;
    public $verification_status;
    public $status;

    public function __construct(
        int $user_id = null,
        string $id = null,
        string $user_email = null,
        string $email_provider = null,
        bool $is_primary = null,
        string $last_verification_sent_at = null,
        string $verification_code_expiry = null,
        string $verification_status = null,
        string $status = null,
    ) {
        $this->user_id = $user_id;
        $this->id = $id;
        $this->user_email = $user_email;
        $this->email_provider = $email_provider;
        $this->is_primary = $is_primary;
        $this->last_verification_sent_at = $last_verification_sent_at;
        $this->verification_code_expiry = $verification_code_expiry;
        $this->verification_status = $verification_status;
        $this->status = $status;
    }

    public function toArray(): array {
        return [
            'id' => $this->id,
            'email' => $this->email,
            'is_primary' => $this->is_primary,
            'verification_status' => $this->verification_status,
            'status' => $this->status,
            'created_date' => $this->created_date
        ];
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

//----- File: app-microservice-user-email/data/model/user-email-select-request-model.php -----

<?php
namespace App\Microservice\Schema\Data\Model\User\Email;
?>
<?php
class UserEmailSelectRequestModel {
    public $user_id;
    public $user_email;
    public $email_provider;
    public $is_primary;
    public $verification_code;
    public $last_verification_sent_at;
    public $verification_code_expiry;
    public $verification_status;
    public $status;
    public mixed $columns;
    public $action_type;

    public function __construct(
        $user_id = null,
        $user_email = null,
        $email_provider = null,
        $is_primary = null,
        $verification_code = null,
        $columns = null,
        $action_type = null,
    ) {
        $this->user_id = $user_id;
        $this->user_email = $user_email;
        $this->email_provider = $email_provider;
        $this->is_primary = $is_primary;
        $this->verification_code = $verification_code;
        $this->columns = $columns;
        $this->action_type = $action_type;
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

//----- File: app-microservice-user-email/data/repository/user-email-repository-impl.php -----

<?php
namespace App\Microservice\Data\Repository\User\Email;
?>
<?php
use App\Microservice\Domain\Repository\User\Email\UserEmailRepository;
use App\Microservice\Schema\Domain\Model\User\Email\UserEmailEntity;
use App\Microservice\Core\Utils\Database\Database;
use App\Microservice\Core\Utils\Data\Response\ResponseData;
use App\Microservice\Core\Utils\Type\Response\ResponseStatus;
use App\Microservice\Data\Mapper\User\Email\UserEmailMapper;
use RzSDK\Utils\ObjectPropertyWizard;
use RzSDK\Database\SqliteFetchType;
?>
<?php
class UserEmailRepositoryImpl implements UserEmailRepository {
    private $dbConn;
    public static $useEmailTableName = "tbl_user_email";

    public function __construct() {
        $this->dbConn = (new Database())->getConnection();
    }

    public function create(UserEmailEntity $userEmail): ResponseData {
        $tempUserEmailEntity = UserEmailEntity::mapToEntityColumn();
        $useEmailTableName = self::$useEmailTableName;
        //
        $colUserId = $tempUserEmailEntity->user_id;
        $colUserIdValue = $userEmail->user_id;
        $colEmail = $tempUserEmailEntity->email;
        $colEmailNameValue = $userEmail->email;
        $colIsPrimary = $tempUserEmailEntity->is_primary;
        $colIsPrimaryValue = $userEmail->is_primary;
        //
        $sqlQuery = "SELECT * FROM {$useEmailTableName} WHERE {$colEmail} = '{$colEmailNameValue}';";
        $dbResult = $this->dbConn->query($sqlQuery, SqliteFetchType::FETCH_OBJ);
        //print_r($dbResult);
        if(!empty($dbResult)) {
            return $this->getResponse(
                "'{$userEmail->email}' email already exists.",
                ResponseStatus::ERROR,
                $userEmail,
                409,
            );
        }
        //
        $sqlQuery = "SELECT * FROM {$useEmailTableName} WHERE {$colUserId} = '{$colUserIdValue}';";
        $dbResult = $this->dbConn->query($sqlQuery, SqliteFetchType::FETCH_OBJ);
        //print_r($dbResult);
        if(empty($dbResult)) {
            $userEmail->is_primary = true;
        } else {
            //echo gettype($userEmailEntity->is_primary);
            if($userEmail->is_primary) {
                $sqlQuery = "SELECT * FROM {$useEmailTableName} WHERE {$colUserId} = '{$colUserIdValue}' AND {$colIsPrimary} = true;";
                //echo $sqlQuery;
                //$sqlQuery = "SELECT * FROM {$useEmailTableName} WHERE {$colUserId} = '{$colUserIdValue}';";
                //echo $sqlQuery;
                $dbResult = $this->dbConn->query($sqlQuery);
                //print_r($dbResult);
                if(!empty($dbResult)) {
                    $entityDataList = UserEmailMapper::mapDbToEntity($dbResult);
                    //print_r($entityDataList);
                    if(!empty($entityDataList)) {
                        $entityData = $entityDataList[0];
                        $entityData->is_primary = false;
                        $entityData->modified_date = date("Y-m-d H:i:s");
                        $entityData->modified_by = $userEmail->modified_by;
                        $this->updatePrimaryEmail($entityData);
                    }
                }
            }
        }
        $userEmail->is_primary = false;
        if($userEmail->is_primary || strtolower($userEmail->is_primary) == "true") {
            $userEmail->is_primary = true;
        }
        //
        $dataVarListWithKey = $tempUserEmailEntity->getVarListWithKey();
        $columns = "";
        $values = "";
        foreach($dataVarListWithKey as $value) {
            $columns .= "$value, ";
            $values .= ":$value, ";
        }
        $columns = trim(trim($columns), ",");
        $values = trim(trim($values), ",");
        $sqlQuery = "INSERT INTO {$useEmailTableName} ($columns) VALUES ($values)";
        $params = [];
        $dataList = $userEmail->getVarListWithKey();
        foreach($dataList as $key => $value) {
            $params[":$key"] = $value;
        }
        //print_r($userEmailEntity);
        //print_r($params);
        $this->dbConn->execute($sqlQuery, $params);
        return new ResponseData("User email created successfully.", ResponseStatus::SUCCESS, $userEmail, 201);
    }

    public function select(UserEmailEntity $userEmail, array $columns): ResponseData {
        $useEmailTableName = self::$useEmailTableName;
        $columns = array_map(function ($item) {
            if ($item === "user_email") return "email";
            if ($item === "email_provider") return "provider";
            return $item;
        }, $columns);
        //print_r($columns);
        $varList = $userEmail->getVarList();
        $whereParts = array();
        foreach ($columns as $key) {
            if(in_array($key, $varList)) {
                $value = $userEmail->{$key};
                $whereParts[] = "$key = '{$value}'";
            }
        }
        $whereClause = implode(' AND ', $whereParts);
        $whereStatement = "WHERE $whereClause;";
        if(empty($whereStatement)) {
            $whereStatement = ";";
        }
        $sqlQuery = trim("SELECT * FROM {$useEmailTableName} $whereStatement");
        $dbResult = $this->dbConn->query($sqlQuery);
        $entityDataList = array();
        if(!empty($dbResult)) {
            $entityDataList = UserEmailMapper::mapDbToEntity($dbResult);
        } else {
            return new ResponseData("User email not found.", ResponseStatus::ERROR, $entityDataList, 404);
        }
        if(empty($entityDataList)) {
            return new ResponseData("Password not found", ResponseStatus::ERROR, $entityDataList, 404);
        }
        return new ResponseData("User email selected successfully.", ResponseStatus::SUCCESS, $entityDataList, 200);
    }

    public function updatePrimaryEmail(UserEmailEntity $userEmail) {
        $tempUserEmailEntity = UserEmailEntity::mapToEntityColumn();
        $useEmailTableName = self::$useEmailTableName;
        //
        $colUserId = $tempUserEmailEntity->user_id;
        $colUserIdValue = $userEmail->user_id;
        $colEmail = $tempUserEmailEntity->email;
        $colEmailNameValue = $userEmail->email;
        $colIsPrimary = $tempUserEmailEntity->is_primary;
        $colIsPrimaryValue = $userEmail->is_primary;
        $colModifiedBy = $tempUserEmailEntity->modified_by;
        $colModifiedByValue = $userEmail->modified_by;
        $colModifiedDate = $tempUserEmailEntity->modified_date;
        $colModifiedDateValue = $userEmail->modified_date;
        //
        $sqlQuery = "UPDATE $useEmailTableName SET {$colIsPrimary} = :{$colIsPrimary}, {$colModifiedBy} = :{$colModifiedBy}, {$colModifiedDate} = :{$colModifiedDate} WHERE {$colUserId} = :{$colUserId} AND {$colEmail} = :{$colEmail}";
        //echo $sqlQuery;
        $params = array(
            ":{$colUserId}" => $colUserIdValue,
            ":{$colEmail}" => $colEmailNameValue,
            ":{$colIsPrimary}" => $colIsPrimaryValue,
            ":{$colModifiedBy}" => $colModifiedByValue,
            ":{$colModifiedDate}" => $colModifiedDateValue,
        );
        //print_r($params);
        $this->dbConn->execute($sqlQuery, $params);
        return new ResponseData("User email updated successfully.", ResponseStatus::SUCCESS, $userEmail);
    }

    public function getResponse($message, ResponseStatus $status, $responseData, $statusCode = 200) {
        return new ResponseData($message, $status, $responseData, $statusCode);
    }

    /*public function save(CompositeKeyModel $compositeKey): void {
        $data = TableDataMapper::toDomain($compositeKey);

        if($compositeKey->id) {
            // Update
            $stmt = $this->db->prepare("UPDATE tbl_table_data SET ... WHERE id = :id");
            $stmt->execute($data);
        } else {
            // Insert
            $stmt = $this->db->prepare("INSERT INTO tbl_table_data (...) VALUES (...)");
            $stmt->execute($data);
            $compositeKey->id = $this->db->lastInsertId();
        }

    for($i = 0; $i < count($dataVarList); $i++) {
            $dataList[$dataVarList[$i]] = $tableData->{$domainVarList[$i]};
        }

    foreach($data as $key => $value) {
            $params[":$key"] = $value;
        }
    }*/

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
<?php
/*class UserEmailRequestTest {
    public $user_id;
    public $email;
    public $provider;
    public $is_primary;
    public $verification_code;
    public $action_type;
}
$userEmailRequest = new UserEmailRequestTest();
$userEmailRequest->is_primary = false;
$type = gettype($userEmailRequest->is_primary);
echo $type;
$var = 123;
echo gettype($var);*/
/*- echo $userEmailRequest->user_id output int
- echo $userEmailRequest->email output string
- echo $userEmailRequest->$is_primary output boolean*/
?>


//----- File: app-microservice-user-email/di/user-email-module.php -----

<?php
namespace App\Microservice\Dependency\Injection\Module\Use\Email;
?>
<?php
use App\Microservice\Domain\Repository\User\Email\UserEmailRepository;
use App\Microservice\Data\Repository\User\Email\UserEmailRepositoryImpl;
use App\Microservice\Domain\UseCase\User\Email\UserEmailUseCase;
use App\Microservice\Presentation\ViewModel\Use\Email\UserEmailViewModel;
?>
<?php
class UserEmailModule {
    private function provideRepository(): UserEmailRepository {
        return new UserEmailRepositoryImpl();
    }

    private function provideUseCase(UserEmailRepository $repository): UserEmailUseCase {
        return new UserEmailUseCase($repository);
    }

    public function provideViewModel(): UserEmailViewModel {
        $repository = $this->provideRepository();
        $useCase = $this->provideUseCase($repository);
        return new UserEmailViewModel($useCase);
    }
}
?>


//----- File: app-microservice-user-email/domain/model/user-email-entity.php -----

<?php
namespace App\Microservice\Schema\Domain\Model\User\Email;
?>
<?php
class UserEmailEntity {
    public $user_id;
    public $id;
    public $email;
    public $provider;
    public $is_primary;
    public $verification_code;
    public $last_verification_sent_at;
    public $verification_code_expiry;
    public $verification_status;
    public $status;
    public $created_date;
    public $modified_date;
    public $created_by;
    public $modified_by;

    public function __construct(
        $user_id = null,
        $id = null,
        $email = null,
        $provider = null,
        $is_primary = null,
        $verification_code = null,
        $last_verification_sent_at = null,
        $verification_code_expiry = null,
        $verification_status = null,
        $status = null,
        $created_date = null,
        $modified_date = null,
        $created_by = null,
        $modified_by = null
    ) {
        $this->user_id = $user_id;
        $this->id = $id;
        $this->email = $email;
        $this->provider = $provider;
        $this->is_primary = $is_primary;
        $this->verification_code = $verification_code;
        $this->last_verification_sent_at = $last_verification_sent_at;
        $this->verification_code_expiry = $verification_code_expiry;
        $this->verification_status = $verification_status;
        $this->status = $status;
        $this->created_date = $created_date;
        $this->modified_date = $modified_date;
        $this->created_by = $created_by;
        $this->modified_by = $modified_by;
    }

    public static function mapToEntityColumn() {
        return new self(
            "user_id",
            "id",
            "email",
            "provider",
            "is_primary",
            "verification_code",
            "last_verification_sent_at",
            "verification_code_expiry",
            "verification_status",
            "status",
            "created_date",
            "modified_date",
            "created_by",
            "modified_by"
        );
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


//----- File: app-microservice-user-email/domain/repository/user-email-repository.php -----

<?php
namespace App\Microservice\Domain\Repository\User\Email;
?>
<?php
use App\Microservice\Schema\Domain\Model\User\Email\UserEmailEntity;
use App\Microservice\Core\Utils\Data\Response\ResponseData;
?>
<?php
interface UserEmailRepository {
    public function create(UserEmailEntity $userEmail): ResponseData;
    public function select(UserEmailEntity $userEmail, array $columns): ResponseData;
    /*
    public function findByEmail(string $email): ?UserEmailModel;
    public function findById(string $id): ?UserEmailModel;
    public function findByUserId(string $user_id): array;
    public function findPrimaryByUserId(string $user_id): ?UserEmailModel;
    public function updateVerificationStatus(string $id, string $status): bool;
    public function setPrimary(string $user_id, string $id): bool;
    public function remove(string $id): bool;
    public function setVerificationCode(string $id, string $code, string $expiry): bool;*/
}
?>

//----- File: app-microservice-user-email/domain/usecase/user-email-use-case.php -----

<?php
namespace App\Microservice\Domain\UseCase\User\Email;
//namespace App\Microservice\Schema\Data\Services\User\Email;
?>
<?php
use RzSDK\Identification\UniqueIntId;
use App\Microservice\Core\Utils\Data\Response\ResponseData;
use App\Microservice\Core\Utils\Type\Response\ResponseStatus;
use App\Microservice\Domain\Repository\User\Email\UserEmailRepository;
use App\Microservice\Schema\Data\Model\User\Email\UserEmailInsertRequestModel;
use App\Microservice\Schema\Data\Model\User\Email\UserEmailModel;
use App\Microservice\Data\Mapper\User\Email\UserEmailMapper;
use App\Microservice\Schema\Domain\Model\User\Email\UserEmailEntity;
use App\Microservice\Type\Verification\Status\Email\EmailVerificationStatus;
use App\Microservice\Schema\Data\Model\User\Email\UserEmailSelectRequestModel;
?>
<?php
class UserEmailUseCase {
    private UserEmailRepository $repository;

    public function __construct(UserEmailRepository $repository) {
        $this->repository = $repository;
    }

    public function createEmail(UserEmailInsertRequestModel $userEmail): ResponseData {
        $userEmailModel = new UserEmailModel();
        $uniqueIntId = new UniqueIntId();
        //
        $userEmailModel->user_id = $userEmail->user_id;
        $userEmailModel->id = $uniqueIntId->getId();
        $userEmailModel->email = $userEmail->user_email;
        $userEmailModel->provider = $userEmail->email_provider;
        $userEmailModel->is_primary = false;
        if($userEmail->is_primary && strtolower($userEmail->is_primary) == "true") {
            $userEmailModel->is_primary = true;
        }
        //$userEmailModel->is_primary = $userEmail->is_primary;
        $userEmailModel->verification_code = $userEmail->verification_code;
        $userEmailModel->last_verification_sent_at = null;
        $userEmailModel->verification_code_expiry = null;
        $userEmailModel->verification_status = EmailVerificationStatus::PENDING->value;
        $userEmailModel->status = "active";
        $userEmailModel->created_date = date("Y-m-d H:i:s");
        $userEmailModel->modified_date = date("Y-m-d H:i:s");
        $userEmailModel->created_by = $userEmail->user_id;
        $userEmailModel->modified_by = $userEmail->user_id;
        //
        $userEmailEntity = UserEmailMapper::mapModelToEntity($userEmailModel);
        //
        $response = $this->repository->create($userEmailEntity);
        //
        $message = $response->message;
        $status = $response->status;
        $statusCode = $response->status_code;
        $responseData = UserEmailMapper::mapEntityToResponseDto($response->data);
        //
        //return new ResponseData("User email created successfully.", ResponseStatus::SUCCESS, $userEmailEntity, 201);
        return new ResponseData($message, $status, $responseData, $statusCode);
    }

    public function selectEmail(UserEmailSelectRequestModel $userEmail): ResponseData {
        $userEmailModel = new UserEmailModel();
        //
        $userEmailModel->user_id = $userEmail->user_id;
        $userEmailModel->email = $userEmail->user_email;
        $userEmailModel->provider = $userEmail->email_provider;
        $userEmailModel->is_primary = false;
        if($userEmail->is_primary && strtolower($userEmail->is_primary) == "true") {
            $userEmailModel->is_primary = true;
        }
        $userEmailModel->verification_code = $userEmail->verification_code;
        $userEmailModel->last_verification_sent_at = $userEmail->last_verification_sent_at;
        $userEmailModel->verification_code_expiry = $userEmail->verification_code_expiry;
        $userEmailModel->verification_status = $userEmail->verification_status;
        $userEmailModel->status = $userEmail->status;
        $userEmailModel->created_date = null;
        $userEmailModel->modified_date = null;
        $userEmailModel->created_by = null;
        $userEmailModel->modified_by = null;
        $columnList = $userEmail->columns;
        //
        $userEmailEntity = UserEmailMapper::mapModelToEntity($userEmailModel);
        //return new ResponseData("User email selected successfully.", ResponseStatus::SUCCESS, $userEmail, 200);
        $response = $this->repository->select($userEmailEntity, $columnList);
        $message = $response->message;
        $status = $response->status;
        $statusCode = $response->status_code;
        $responseData = UserEmailMapper::mapEntityToResponseDto($response->data);
        return new ResponseData($message, $status, $responseData, $statusCode);
    }

    /*public function addEmail(string $user_id, AddEmailRequest $request): ResponseData {
        // Check if email already exists
        if ($this->repository->findByEmail($request->email)) {
            return new ResponseData(
                'Email already exists',
                ResponseStatus::ERROR
            );
        }

        $email = new UserEmailModel(
            $user_id,
            $request->email,
            $user_id,
            $user_id,
            'user',
            $request->is_primary
        );

        $email = $this->repository->create($email);

        // Generate and send verification code
        $code = $this->generateVerificationCode();
        $expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));
        $this->repository->setVerificationCode($email->id, $code, $expiry);
        $this->notification->sendVerificationEmail($email->email, $code);

        return new ResponseData(
            'Email added successfully',
            ResponseStatus::SUCCESS,
            new UserEmailResponseDto(
                $email->id,
                $email->email,
                $email->is_primary,
                $email->verification_status,
                $email->status,
                $email->created_date
            )
        );
    }

    public function setPrimaryEmail(string $user_id, SetPrimaryEmailRequest $request): ResponseData {
        $email = $this->repository->findById($request->email_id);

        if (!$email || $email->user_id !== $user_id) {
            return new ResponseData(
                'Email not found',
                ResponseStatus::NOT_FOUND
            );
        }

        if ($email->verification_status !== 'verified') {
            return new ResponseData(
                'Email must be verified before setting as primary',
                ResponseStatus::ERROR
            );
        }

        if ($this->repository->setPrimary($user_id, $email->id)) {
            return new ResponseData(
                'Primary email set successfully',
                ResponseStatus::SUCCESS,
                new UserEmailResponseDto(
                    $email->id,
                    $email->email,
                    true,
                    $email->verification_status,
                    $email->status,
                    $email->created_date
                )
            );
        }

        return new ResponseData(
            'Failed to set primary email',
            ResponseStatus::ERROR
        );
    }

    public function verifyEmail(string $user_id, VerifyEmailRequest $request): ResponseData {
        $email = $this->repository->findById($request->email_id);

        if (!$email || $email->user_id !== $user_id) {
            return new ResponseData(
                'Email not found',
                ResponseStatus::NOT_FOUND
            );
        }

        if ($email->verification_status === 'verified') {
            return new ResponseData(
                'Email already verified',
                ResponseStatus::SUCCESS,
                new UserEmailResponseDto(
                    $email->id,
                    $email->email,
                    $email->is_primary,
                    $email->verification_status,
                    $email->status,
                    $email->created_date
                )
            );
        }

        if (strtotime($email->verification_code_expiry) < time()) {
            $this->repository->updateVerificationStatus($email->id, 'expired');
            return new ResponseData(
                'Verification code expired',
                ResponseStatus::ERROR
            );
        }

        if ($email->verification_code !== $request->verification_code) {
            return new ResponseData(
                'Invalid verification code',
                ResponseStatus::ERROR
            );
        }

        if ($this->repository->updateVerificationStatus($email->id, 'verified')) {
            return new ResponseData(
                'Email verified successfully',
                ResponseStatus::SUCCESS,
                new UserEmailResponseDto(
                    $email->id,
                    $email->email,
                    $email->is_primary,
                    'verified',
                    $email->status,
                    $email->created_date
                )
            );
        }

        return new ResponseData(
            'Failed to verify email',
            ResponseStatus::ERROR
        );
    }

    public function getEmails(string $user_id): ResponseData {
        $emails = $this->repository->findByUserId($user_id);
        $response = [];

        foreach ($emails as $email) {
            $response[] = new UserEmailResponseDto(
                $email->id,
                $email->email,
                $email->is_primary,
                $email->verification_status,
                $email->status,
                $email->created_date
            );
        }

        return new ResponseData(
            'Emails retrieved successfully',
            ResponseStatus::SUCCESS,
            $response
        );
    }

    private function generateVerificationCode(): string {
        return substr(md5(uniqid(mt_rand(), true)), 0, 8);
    }*/
}
?>

//----- File: app-microservice-user-email/include.php -----

<?php
namespace RzSDK\Include\Import;
?>
<?php
$workingDir = __DIR__;
$workingDirName = basename($workingDir);
defined("CONST_STARTING_PATH") or define("CONST_STARTING_PATH", $workingDir);
defined("CONST_WORKING_DIR_NAME") or define("CONST_WORKING_DIR_NAME", $workingDirName);
?>
<?php
require_once("../include.php");
//require_once("../../include.php");
?>
<?php
//echo "==================================Language";
?>
<?php
global $workingBaseUrl;
$workingBaseUrl = BASE_URL . "/" . CONST_WORKING_DIR_NAME;
defined("WORKING_BASE_URL") or define("WORKING_BASE_URL", $workingBaseUrl);
defined("WORKING_ROOT_URL") or define("WORKING_ROOT_URL", $workingBaseUrl);
?>
<?php
/*defined("DB_PATH") or define("DB_PATH", "database");
defined("DB_FILE") or define("DB_FILE", "database-schema-database.sqlite");
defined("DB_FULL_PATH") or define("DB_FULL_PATH", "" . DB_PATH . "/" . DB_FILE);*/
?>

//----- File: app-microservice-user-email/index.php -----

<?php
header("Content-Type: application/json");
?>
<?php
require_once("include.php");
?>
<?php
use App\Microservice\Core\Utils\Data\Response\ResponseData;
use App\Microservice\Core\Utils\Type\Response\ResponseStatus;
use App\Microservice\Presentation\Controller\Use\Email\UserEmailController;
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
$controller = new UserEmailController();
$response = $controller->executeController($_POST);
echo $response->toJson();
?>

//----- File: app-microservice-user-email/presentation/user-email-controller.php -----

<?php
namespace App\Microservice\Presentation\Controller\Use\Email;
?>
<?php
use App\Microservice\Core\Utils\Data\Response\ResponseData;
use App\Microservice\Core\Utils\Type\Response\ResponseStatus;
use App\Microservice\Domain\UseCase\User\Email\UserEmailUseCase;
use App\Microservice\Data\Repository\User\Email\UserEmailRepositoryImpl;
use App\Microservice\Dependency\Injection\Module\Use\Email\UserEmailModule;
use App\Microservice\Presentation\ViewModel\Use\Email\UserEmailViewModel;
?>
<?php
class UserEmailController {
    private UserEmailViewModel $viewModel;
    public function __construct() {
        $this->viewModel = (new UserEmailModule())->provideViewModel();
    }

    public function executeController(array $requestDataSet): ResponseData {
        try {
            if (empty($requestDataSet["action_type"])) {
                return new ResponseData("Missing required field: action_type", ResponseStatus::ERROR, 404);
            }
            return $this->viewModel->executeViewModel($requestDataSet);
        } catch (\Exception $e) {
            return new ResponseData("Failed to create user email: " . $e->getMessage(), ResponseStatus::ERROR);
        }
    }
}
?>

//----- File: app-microservice-user-email/presentation/user-email-view-model.php -----

<?php
namespace App\Microservice\Presentation\ViewModel\Use\Email;
?>
<?php
use App\Microservice\Core\Utils\Data\Response\ResponseData;
use App\Microservice\Schema\Data\Model\User\Email\UserEmailInsertRequestModel;
use App\Microservice\Domain\UseCase\User\Email\UserEmailUseCase;
use App\Microservice\Type\Action\Email\EmailActionType;
use App\Microservice\Core\Utils\Type\Response\ResponseStatus;
use App\Microservice\Schema\Data\Model\User\Email\UserEmailSelectRequestModel;
?>
<?php
class UserEmailViewModel {
    private $useCase;

    public function __construct(UserEmailUseCase $useCase) {
        $this->useCase = $useCase;
    }

    public function executeViewModel(array $requestDataSet): ResponseData {
        $actionType = EmailActionType::getByValue($requestDataSet["action_type"]);
        if ($actionType == EmailActionType::INSERT) {
            return $this->createEmail($requestDataSet);
        } else if ($actionType == EmailActionType::SELECT) {
            return $this->selectEmail($requestDataSet);
        }
        return new ResponseData("Missing requested action type", ResponseStatus::ERROR, $requestDataSet, 404);
    }

    public function createEmail(array $requestDataSet): ResponseData {
        $requiredFields = array(
            "user_id",
            "action_type",
        );
        foreach ($requiredFields as $field) {
            if (empty($requestDataSet[$field])) {
                return new ResponseData("Missing required field: $field", ResponseStatus::ERROR, 400);
            }
        }
        //
        $userEmailRequestModel = new UserEmailInsertRequestModel();
        $varList = $userEmailRequestModel->getVarList();
        foreach ($varList as $key) {
            if (array_key_exists($key, $requestDataSet)) {
                $userEmailRequestModel->{$key} = $requestDataSet[$key];
            }
        }
        //
        //return new ResponseData("User email created successfully.", ResponseStatus::SUCCESS, $userEmailModel, 201);
        return $this->useCase->createEmail($userEmailRequestModel);
    }

    public function selectEmail(array $requestDataSet): ResponseData {
        $requiredFields = array(
            "action_type",
        );
        foreach ($requiredFields as $field) {
            if (empty($requestDataSet[$field])) {
                return new ResponseData("Missing required field: $field", ResponseStatus::ERROR, 400);
            }
        }
        $userEmail = new UserEmailSelectRequestModel();
        //
        $dataVarList = $userEmail->getVarList();
        foreach ($dataVarList as $key) {
            if(array_key_exists($key, $requestDataSet)) {
                $userEmail->{$key} = $requestDataSet[$key];
            }
        }
        //
        $columnRawData = $userEmail->columns;
        if (!empty($columnRawData)) {
            if (is_array($columnRawData)) {
                $userEmail->columns = $columnRawData;
            } else if (is_string($columnRawData)) {
                $decoded = json_decode($columnRawData, true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                    $userEmail->columns = $decoded;
                } else {
                    // Treat as comma-separated string
                    $userEmail->columns = array_map('trim', explode(',', $columnRawData));
                }
            }
        }
        //
        //return new ResponseData("User email selected successfully.", ResponseStatus::SUCCESS, $requestDataSet, 200);
        return $this->useCase->selectEmail($userEmail);
    }
}
?>

//----- File: app-microservice-user-email/utility/email-action-type.php -----

<?php
namespace App\Microservice\Type\Action\Email;
?>
<?php
enum EmailActionType: string {
    case GET = "get";
    case FIND = "find";
    case SELECT = "select";
    case INSERT = "insert";
    case UPDATE = "update";

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

//----- File: app-microservice-user-email/utility/email-verification-status.php -----

<?php
namespace App\Microservice\Type\Verification\Status\Email;
?>
<?php
enum EmailVerificationStatus: string {
    // pending is default status
    case PENDING = "pending";
    case VERIFIED = "verified";
    case EXPIRED = "expired";
    case BLOCKED = "blocked";

    public static function getByName(string $value): ?self {
        foreach (self::cases() as $case) {
            if ($case->name === $value) {
                return $case;
            }
        }
        return self::PENDING;
    }

    public static function getByValue(string $value): ?self {
        foreach (self::cases() as $case) {
            if ($case->value === $value) {
                return $case;
            }
        }
        return self::PENDING;
    }
}
?>


- mvvm clean architecture directory structure
- provide directory file name location top of codebase
- mvvm clean architecture user email in php api only
- mvvm clean architecture data layer, domain layer, presentation layer
- mvvm clean architecture different user input request data layer, database insert data layer, response data layer
- use response for ResponseData class

- use UserEmailRepository class, UserEmailRepositoryImpl class, UserEmailUseCase class, UserEmailViewModel class, UserEmailController class

- UserEmailViewModel class public function __construct(UserEmailUseCase $useCase)
- UserEmailUseCase class ppublic function __construct(UserEmailRepository $repository)
- UserEmailRepositoryImpl class public function __construct()

- also provide how to use usage of UserEmailController class or call UserEmailController class

*** refactor and optimize code if needed
*** restructure file and folder if needed
*** don't break code consistency
*** code or directory refactor if needed
*** code or directory refactor if better options
*** Don't change class name if not needed
*** Don't change unwanted codebase
*** provide full code
*** Provide Full Codebase
*** read full document properly