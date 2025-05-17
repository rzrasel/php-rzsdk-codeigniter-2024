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