<?php
namespace App\Microservice\Dependency\Injection\Module\Use\Password;
?>
<?php
use App\Microservice\Core\Utils\Type\Database\DatabaseType;
use App\Microservice\Data\DataSources\User\Password\UserPasswordDataSourceImpl;
use App\Microservice\Data\DataSources\User\Password\UserPasswordDataSource;
use App\Microservice\Data\Repository\User\Password\UserPasswordRepositoryImpl;
use App\Microservice\Domain\Repository\User\Password\UserPasswordRepository;
use App\Microservice\Domain\UseCase\User\Password\UserPasswordUseCase;
use App\Microservice\Presentation\ViewModel\Use\Password\UserPasswordViewModel;
?>
<?php
class UserPasswordModule {
    private function provideDbType() {
        return DatabaseType::SQLITE;
    }

    private function provideDataSource(DatabaseType $dbType): UserPasswordDataSource {
        return new UserPasswordDataSourceImpl($dbType);
    }

    private function provideRepository(UserPasswordDataSource $dataSource): UserPasswordRepository {
        return new UserPasswordRepositoryImpl($dataSource);
    }

    private function provideUseCase(UserPasswordRepository $repository): UserPasswordUseCase {
        return new UserPasswordUseCase($repository);
    }

    public function provideViewModel(): UserPasswordViewModel {
        $dbType = $this->provideDbType();
        $dataSource = $this->provideDataSource($dbType);
        $repository = $this->provideRepository($dataSource);
        $useCase = $this->provideUseCase($repository);
        return new UserPasswordViewModel($useCase);
    }
}
?>