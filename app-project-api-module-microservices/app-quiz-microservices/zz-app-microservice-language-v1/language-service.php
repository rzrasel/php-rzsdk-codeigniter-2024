<?php
namespace App\Microservice\Domain\Service\Language;
?>
<?php
use App\Microservice\Domain\Repository\Language\LanguageRepository;
use App\Microservice\Schema\Domain\Model\Language\LanguageEntity;
?>
<?php
class LanguageService {
    private $repository;

    public function __construct(LanguageRepository $repository) {
        $this->repository = $repository;
    }

    public function getLanguageById(int $id): ?LanguageEntity {
        return $this->repository->findById($id);
    }

    public function getAllLanguages(): array {
        return $this->repository->findAll();
    }

    public function createLanguage(LanguageEntity $language): void {
        $this->repository->save($language);
    }

    public function updateLanguage(LanguageEntity $language): void {
        $this->repository->update($language);
    }

    public function deleteLanguage(int $id): void {
        $this->repository->delete($id);
    }
}
?>
