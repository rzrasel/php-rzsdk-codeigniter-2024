<?php
namespace App\Microservice\Presentation\Controller\Use\Email;
?>
<?php
use App\Microservice\Schema\Data\Services\User\Email\EmailService;
?>
<?php
class UserEmailController {
    public function __construct(
        private UserEmailService $service
    ) {}

    public function addEmail(array $input): array {
        $user_id = AuthMiddleware::getAuthenticatedUserId();
        $request = new AddEmailRequest($input);

        $response = $this->service->addEmail($user_id, $request);
        return $response->toArray();
    }
}
?>