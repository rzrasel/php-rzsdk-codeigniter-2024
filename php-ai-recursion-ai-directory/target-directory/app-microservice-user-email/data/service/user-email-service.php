<?php
namespace App\Microservice\Schema\Data\Services\User\Email;
?>
<?php
use App\Microservice\Core\Utils\Data\Response\ResponseData;
use App\Microservice\Core\Utils\Type\Response\ResponseStatus;
use App\Microservice\Domain\Repository\User\Email\EmailRepository;
use App\Microservice\Schema\Domain\Model\User\Email\UserEmailModel;
use App\Microservice\Schema\Data\Model\User\Email\UserEmailResponseDto;
?>
<?php
class UserEmailService {
    private UserEmailRepository $repository;
    private EmailNotification $notification;

    public function __construct(
        EmailRepository $repository,
        EmailNotification $notification
    ) {
        $this->repository = $repository;
        $this->notification = $notification;
    }

    public function addEmail(string $user_id, AddEmailRequest $request): ResponseData {
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
    }
}
?>