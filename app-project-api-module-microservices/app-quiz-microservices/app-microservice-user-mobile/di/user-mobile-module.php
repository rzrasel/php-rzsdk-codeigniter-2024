<?php
namespace App\Microservice\Dependency\Injection\Module\Use\Mobile;
?>
<?php
use App\Microservice\Data\Data\Access\Object\User\Mobile\UserMobileLocalDAO;
use App\Microservice\Data\Data\Access\Object\User\Mobile\UserMobileRemoteDAO;
use App\Microservice\Data\Data\Source\User\Mobile\UserMobileDataSource;
use App\Microservice\Data\Data\Source\User\Mobile\UserMobileDataSourceImpl;
use App\Microservice\Domain\Repository\User\Mobile\UserMobileRepository;
use App\Microservice\Data\Repository\User\Mobile\UserMobileRepositoryImpl;
use App\Microservice\Domain\Usecase\User\Mobile\UserMobileUseCase;
use App\Microservice\Presentation\ViewModel\Use\Mobile\UserMobileViewModel;
?>
<?php
class UserMobileModule {

    private function provideLocalDAO(): UserMobileLocalDAO {
        return new UserMobileLocalDAO();
    }

    private function provideRemoteDAO(): UserMobileRemoteDAO {
        return new UserMobileRemoteDAO();
    }

    private function provideDataSource(UserMobileLocalDAO $localDao, UserMobileRemoteDAO $remoteDao): UserMobileDataSource {
        return new UserMobileDataSourceImpl($localDao, $remoteDao);
    }

    private function provideRepository(UserMobileDataSource $dataSource): UserMobileRepository {
        return new UserMobileRepositoryImpl($dataSource);
    }

    private function provideUseCase(UserMobileRepository $repository): UserMobileUseCase {
        return new UserMobileUseCase($repository);
    }

    public function provideViewModel(): UserMobileViewModel {
        $localDao = $this->provideLocalDAO();
        $remoteDao = $this->provideRemoteDAO();
        $dataSource = $this->provideDataSource($localDao, $remoteDao);
        $repository = $this->provideRepository($dataSource);
        $useCase = $this->provideUseCase($repository);
        return new UserMobileViewModel($useCase);
    }
}
?>
