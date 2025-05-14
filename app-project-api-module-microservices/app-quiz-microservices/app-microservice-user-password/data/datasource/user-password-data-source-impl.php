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
use App\Microservice\Data\Mapper\User\Password\UserPasswordMapper;
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

    public function update(UserPasswordEntity $userPassword): ResponseData {
        $usePasswordTableName = self::$usePasswordTableName;
        $tempUserPasswordEntity = UserPasswordEntity::mapToEntityColumn();
        $fullEntityKeyValueList = array();
        foreach ($tempUserPasswordEntity as $key => $value) {
            $fullEntityKeyValueList[$key] = $userPassword->{$value};
        }
        $extraKeyList = array(
            $tempUserPasswordEntity->created_date,
            $tempUserPasswordEntity->created_by,
        );
        $unsetKeyList = array(
            $tempUserPasswordEntity->user_id,
            $tempUserPasswordEntity->id,
            $tempUserPasswordEntity->created_date,
            $tempUserPasswordEntity->created_by,
        );
        $whereKeyList = array(
            $tempUserPasswordEntity->user_id,
            $tempUserPasswordEntity->id,
        );

        $params = array();
        $setParts = array();
        $whereParts = array();
        foreach ($fullEntityKeyValueList as $key => $value) {
            if(!in_array($key, $extraKeyList)) {
                $params[":$key"] = $value;
                if(!in_array($key, $unsetKeyList)) {
                    $setParts[] = "$key = :$key";
                }
                if(in_array($key, $whereKeyList)) {
                    $whereParts[] = "$key = :$key";
                }
            }
        }
        $setClause = implode(', ', $setParts);
        $whereClause = implode(' AND ', $whereParts);
        /*$updateDataSet = trim(trim($updateDataSet), ",");
        $whereClause = implode(" AND ", $whereList);*/
        $sqlQuery = "UPDATE " . self::$usePasswordTableName . "
                    SET {$setClause}
                    WHERE {$whereClause};";
        //print_r($sqlQuery);
        $this->dbConn->execute($sqlQuery, $params);
        return new ResponseData("Password updated successfully", ResponseStatus::SUCCESS, $userPassword, 204);
    }

    public function select(UserPasswordEntity $userPassword, array $whereColumns): ResponseData {
        $usePasswordTableName = self::$usePasswordTableName;
        $varList = $userPassword->getVarList();
        $whereParts = array();
        foreach ($whereColumns as $key) {
            if(in_array($key, $varList)) {
                $value = $userPassword->{$key};
                $whereParts[] = "$key = '{$value}'";
            }
        }
        $whereClause = implode(' AND ', $whereParts);
        $sqlQuery = "SELECT * FROM {$usePasswordTableName} WHERE {$whereClause};";
        $dbResult = $this->dbConn->query($sqlQuery);
        $entityDataList = array();
        if(!empty($dbResult)) {
            $entityDataList = UserPasswordMapper::mapDbToEntity($dbResult);
        } else {
            return new ResponseData("Password not found", ResponseStatus::ERROR, $entityDataList, 404);
        }
        if(empty($entityDataList)) {
            return new ResponseData("Password not found", ResponseStatus::ERROR, $entityDataList, 404);
        }
        //UserPasswordMapper
        //print_r($entityDataList);
        return new ResponseData("Password selected successfully", ResponseStatus::SUCCESS, $entityDataList, 200);
    }

    public function isActivePasswordExists(UserPasswordEntity $userPassword): ResponseData {
        $retValue = false;
        $usePasswordTableName = self::$usePasswordTableName;
        $tempUserPasswordEntity = UserPasswordEntity::mapToEntityColumn();
        //
        $colUserId = $tempUserPasswordEntity->user_id;
        $colUserIdValue = $userPassword->user_id;
        $colStatus = $tempUserPasswordEntity->status;
        $colStatusValue = PasswordStatus::ACTIVE->value;
        // password exists by user id and status
        $sqlQuery = "SELECT * FROM {$usePasswordTableName} WHERE {$colUserId} = '{$colUserIdValue}' AND {$colStatus} = '{$colStatusValue}';";
        //echo $sqlQuery;
        $dbResult = $this->dbConn->query($sqlQuery, SqliteFetchType::FETCH_OBJ);
        //print_r($dbResult);
        if(!empty($dbResult)) {
            $retValue = true;
        }
        return new ResponseData("Password already exists", ResponseStatus::ERROR, $retValue, 409);
    }
}
?>