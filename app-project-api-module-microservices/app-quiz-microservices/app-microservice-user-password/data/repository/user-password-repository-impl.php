<?php
namespace App\Microservice\Data\Repository\User\Password;
?>
<?php
use App\Microservice\Domain\Repository\User\Password\UserPasswordRepository;
use App\Microservice\Data\DataSources\User\Password\UserPasswordDataSource;
use App\Microservice\Schema\Data\Model\User\Password\UserPasswordModel;
use App\Microservice\Core\Utils\Data\Response\ResponseData;
use App\Microservice\Schema\Domain\Model\User\Password\UserPasswordEntity;
use App\Microservice\Core\Utils\Type\Response\ResponseStatus;
use App\Microservice\Data\Mapper\User\Password\UserPasswordMapper;
use RzSDK\Encryption\PasswordEncryption;
use App\Microservice\Type\Hash\Password\PasswordHashType;
use App\Microservice\Type\Status\Password\PasswordStatus;
?>
<?php
class UserPasswordRepositoryImpl implements UserPasswordRepository {
    private UserPasswordDataSource $dataSource;

    public function __construct(UserPasswordDataSource $dataSource) {
        $this->dataSource = $dataSource;
    }

    public function create(UserPasswordModel $userPassword): ResponseData {
        $userPasswordEntity = UserPasswordMapper::mapModelToEntity($userPassword);
        //return $this->dataSource->create($userPasswordEntity);
        if($userPassword->hash_type == PasswordHashType::PASSWORD_HASH) {
            $passwordEncryption = new PasswordEncryption();
            $userPasswordEntity->password_hash = $passwordEncryption->getPassword($userPasswordEntity->password_hash);
        }
        //
        /*$passwordHashType = $userPassword->hash_type;
        $passwordStatus = $userPassword->status;*/
        /*$passwordHashType = PasswordHashType::getByName($passwordHashType->name);
        $passwordStatus = PasswordStatus::getByName($passwordStatus->name);*/
        //
        $userPasswordEntity->hash_type = $userPassword->hash_type->value;
        $userPasswordEntity->status = $userPassword->status->value;
        //return new ResponseData("Password created successfully", ResponseStatus::SUCCESS, $userPasswordEntity, 201);
        $response = $this->dataSource->isActivePasswordExists($userPasswordEntity);
        $isExists = $response->data;
        if($isExists) {
            $message = $response->message;
            $status = $response->status;
            $statusCode = $response->status_code;
            $responseData = UserPasswordMapper::mapModelToResponseModel($userPassword);
            return new ResponseData($message, $status, $responseData, $statusCode);
        }
        $response = $this->dataSource->create($userPasswordEntity);
        //
        $message = $response->message;
        $status = $response->status;
        $statusCode = $response->status_code;
        $responseData = UserPasswordMapper::mapEntityToResponseModel($response->data);
        //print_r($responseData);
        //
        return new ResponseData($message, $status, $responseData, $statusCode);
    }

    public function update(UserPasswordModel $userPassword): ResponseData {
        $userPasswordEntity = UserPasswordMapper::mapModelToEntity($userPassword);
        if($userPassword->hash_type == PasswordHashType::PASSWORD_HASH) {
            $passwordEncryption = new PasswordEncryption();
            $userPasswordEntity->password_hash = $passwordEncryption->getPassword($userPasswordEntity->password_hash);
        }
        //
        $userPasswordEntity->hash_type = $userPassword->hash_type->value;
        $userPasswordEntity->status = $userPassword->status->value;
        //
        //return new ResponseData("Password updated successfully", ResponseStatus::SUCCESS, $userPasswordEntity, 204);
        $response = $this->dataSource->update($userPasswordEntity);
        //
        $message = $response->message;
        $status = $response->status;
        $statusCode = $response->status_code;
        $responseData = UserPasswordMapper::mapEntityToResponseModel($response->data);
        return new ResponseData($message, $status, $responseData, $statusCode);
    }

    public function select(UserPasswordModel $userPassword, array $columns): ResponseData {
        $userPasswordEntity = UserPasswordMapper::mapModelToEntity($userPassword);
        //return new ResponseData("Password selected successfully", ResponseStatus::SUCCESS, $userPasswordEntity, 200);
        $response = $this->dataSource->select($userPasswordEntity, $columns);
        $message = $response->message;
        $status = $response->status;
        $statusCode = $response->status_code;
        $responseData = UserPasswordMapper::mapEntityToResponseModel($response->data);
        return new ResponseData($message, $status, $responseData, $statusCode);
    }
}
?>