<?php
namespace App\Microservice\Domain\UseCase\User\Password;
?>
<?php
use App\Microservice\Schema\Domain\Model\User\Password\UserPasswordEntity;
use App\Microservice\Core\Utils\Data\Response\ResponseData;
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