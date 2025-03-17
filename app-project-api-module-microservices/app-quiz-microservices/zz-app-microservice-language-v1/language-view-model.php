<?php
namespace App\Microservice\Presentation\ViewModel\Language;
?>
<?php
use App\Microservice\Domain\Repository\Language\LanguageRepository;
use App\Microservice\Domain\Service\Language\LanguageService;
use App\Microservice\Schema\Domain\Model\Language\LanguageEntity;

?>
<?php
class LanguageViewModel {
    private $service;

    public function __construct(LanguageService $service) {
        $this->service = $service;
    }

    public function getLanguageById(int $id): ?LanguageEntity {
        return $this->service->getLanguageById($id);
    }

    public function getAllLanguages(): array {
        return $this->service->getAllLanguages();
    }

    public function createLanguage(LanguageEntity $language): void {
        $this->service->createLanguage($language);
    }

    public function updateLanguage(LanguageEntity $language): void {
        $this->service->updateLanguage($language);
    }

    public function deleteLanguage(int $id): void {
        $this->service->deleteLanguage($id);
    }
}
?>
<?php
class LanguageViewModelV1 {
    private $repository;

    public function __construct(LanguageRepository $repository) {
        $this->repository = $repository;
    }

    public function getLanguageById(int $id): ?LanguageEntity {
        return $this->service->getLanguageById($id);
    }

    public function getAllLanguages(): array {
        return $this->service->getAllLanguages();
    }

    public function createLanguage(LanguageEntity $language): void {
        $this->service->createLanguage($language);
    }

    public function updateLanguage(LanguageEntity $language): void {
        $this->service->updateLanguage($language);
    }

    public function deleteLanguage(int $id): void {
        $this->service->deleteLanguage($id);
    }
}
?>