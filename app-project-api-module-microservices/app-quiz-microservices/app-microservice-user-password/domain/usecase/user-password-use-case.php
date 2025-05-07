<?php
namespace App\Microservice\Domain\UseCase\User\Password;
?>
<?php
use App\Microservice\Schema\Domain\Model\User\Password\UserPasswordEntity;
use App\Microservice\Schema\Data\Model\User\Password\UserPasswordModel;
use App\Microservice\Core\Utils\Data\Response\ResponseData;
use App\Microservice\Domain\Repository\User\Password\UserPasswordRepository;
use App\Microservice\Schema\Data\Model\User\Password\UserPasswordInsertRequestModel;
use App\Microservice\Core\Utils\Type\Response\ResponseStatus;
use RzSDK\Identification\UniqueIntId;
use App\Microservice\Type\Status\Password\PasswordStatus;
use App\Microservice\Type\Hash\Password\PasswordHashType;
use App\Microservice\Schema\Data\Model\User\Password\UserPasswordUpdateRequestModel;
use App\Microservice\Schema\Data\Model\User\Password\UserPasswordSelectRequestModel;
?>
<?php
class UserPasswordUseCase {
    private UserPasswordRepository $repository;

    public function __construct(UserPasswordRepository $repository) {
        $this->repository = $repository;
    }

    public function createPassword(UserPasswordInsertRequestModel $userPassword): ResponseData {
        $uniqueIntId = new UniqueIntId();
        $userPasswordModel = new UserPasswordModel();
        $userPasswordModel->user_id = $userPassword->user_id;
        $userPasswordModel->id = $uniqueIntId->getId();
        $userPasswordModel->hash_type = PasswordHashType::PASSWORD_HASH;
        $userPasswordModel->password_salt = null;
        $userPasswordModel->password_hash = $userPassword->password;
        $userPasswordModel->expiry = null;
        $userPasswordModel->status = PasswordStatus::ACTIVE;
        $userPasswordModel->modified_date = date("Y-m-d H:i:s");
        $userPasswordModel->created_date = date("Y-m-d H:i:s");
        $userPasswordModel->modified_by = $userPassword->user_id;
        $userPasswordModel->created_by = $userPassword->user_id;
        //return new ResponseData("Password created successfully", ResponseStatus::SUCCESS, $userPassword, 201);
        return $this->repository->create($userPasswordModel);
    }

    public function updatePassword(UserPasswordUpdateRequestModel $userPassword): ResponseData {
        $userPasswordModel = new UserPasswordModel();
        $userPasswordModel->user_id = $userPassword->user_id;
        $userPasswordModel->id = $userPassword->id;
        $userPasswordModel->hash_type = PasswordHashType::PASSWORD_HASH;
        $userPasswordModel->password_salt = null;
        $userPasswordModel->password_hash = $userPassword->password;
        $userPasswordModel->expiry = null;
        $userPasswordModel->status = PasswordStatus::ACTIVE;
        $userPasswordModel->modified_date = date("Y-m-d H:i:s");
        $userPasswordModel->created_date = date("Y-m-d H:i:s");
        $userPasswordModel->modified_by = $userPassword->user_id;
        $userPasswordModel->created_by = $userPassword->user_id;
        //return new ResponseData("Password updated successfully", ResponseStatus::SUCCESS, $userPasswordModel, 204);
        return $this->repository->update($userPasswordModel);
    }

    public function selectPassword(UserPasswordSelectRequestModel $userPassword): ResponseData {
        $userPasswordModel = new UserPasswordModel();
        $userPasswordModel->user_id = $userPassword->user_id;
        $userPasswordModel->id = $userPassword->id;
        $userPasswordModel->hash_type = null;
        $userPasswordModel->password_salt = null;
        $userPasswordModel->password_hash = null;
        $userPasswordModel->expiry = $userPassword->expiry;
        $userPasswordModel->status = $userPassword->status;
        $userPasswordModel->modified_date = null;
        $userPasswordModel->created_date = null;
        $userPasswordModel->modified_by = null;
        $userPasswordModel->created_by = null;
        $columnList = $userPassword->columns;
        //return new ResponseData("Password selected successfully", ResponseStatus::SUCCESS, $userPasswordModel, 200);
        return $this->repository->select($userPasswordModel, $columnList);
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