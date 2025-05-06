<?php
namespace App\Microservice\Data\DataSources\User\Password;
?>
<?php
use App\Microservice\Core\Utils\Database\Database;
use App\Microservice\Core\Utils\Type\Database\DatabaseType;
use App\Microservice\Schema\Domain\Model\User\Password\UserPasswordEntity;
use App\Microservice\Core\Utils\Data\Response\ResponseData;
use App\Microservice\Core\Utils\Type\Response\ResponseStatus;
use App\Microservice\Type\Status\Password\PasswordStatus;
use RzSDK\Database\SqliteFetchType;
?>
<?php
class UserPasswordDataSourceImpl implements UserPasswordDataSource {
    private $dbConn;
    public static $usePasswordTableName = "tbl_user_password";

    public function __construct(DatabaseType $dbType) {
        $this->dbConn = (new Database())->getConnection();
    }

    public function create(UserPasswordEntity $userPassword): ResponseData {
        $usePasswordTableName = self::$usePasswordTableName;
        $tempUserPasswordEntity = UserPasswordEntity::mapToEntityColumn();
        //
        $dataVarListWithKey = $tempUserPasswordEntity->getVarListWithKey();
        $columns = "";
        $values = "";
        foreach($dataVarListWithKey as $value) {
            $columns .= "$value, ";
            $values .= ":$value, ";
        }
        $columns = trim(trim($columns), ",");
        $values = trim(trim($values), ",");
        $sqlQuery = "INSERT INTO {$usePasswordTableName} ($columns) VALUES ($values)";
        $params = [];
        $dataList = $userPassword->getVarListWithKey();
        //print_r($dataList);
        foreach($dataList as $key => $value) {
            $params[":$key"] = $value;
        }
        //print_r($userPassword);
        //print_r($params);
        $this->dbConn->execute($sqlQuery, $params);
        //
        return new ResponseData("Password created successfully", ResponseStatus::SUCCESS, $userPassword, 201);
    }

    public function isPasswordExists(UserPasswordEntity $userPassword): bool {
        $usePasswordTableName = self::$usePasswordTableName;
        $tempUserPasswordEntity = UserPasswordEntity::mapToEntityColumn();
        //
        $colUserId = $tempUserPasswordEntity->user_id;
        $colUserIdValue = $userPassword->user_id;
        $colStatus = $tempUserPasswordEntity->status;
        $colStatusValue = PasswordStatus::ACTIVE->value;
        //
        $sqlQuery = "SELECT * FROM {$usePasswordTableName} WHERE {$colUserId} = '{$colUserIdValue}' AND {$colStatus} = '{$colStatusValue}';";
        //echo $sqlQuery;
        $dbResult = $this->dbConn->query($sqlQuery, SqliteFetchType::FETCH_OBJ);
        //print_r($dbResult);
        if(!empty($dbResult)) {
            return true;
        }
        return false;
    }

    /*public function create(UserPasswordEntity $userPassword): ResponseData {
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
    }*/

    /*public function getById(string $id): ?UserPasswordModel
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
    }*/
}
?>