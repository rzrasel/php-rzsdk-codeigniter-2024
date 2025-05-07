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