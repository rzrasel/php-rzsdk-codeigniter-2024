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
