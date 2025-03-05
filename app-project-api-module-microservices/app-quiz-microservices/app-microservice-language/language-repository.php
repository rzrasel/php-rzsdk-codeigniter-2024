<?php
namespace App\Microservice\Domain\Repository\Language;
?>
<?php
use App\Microservice\Schema\Domain\Model\Language\LanguageEntity;
?>
<?php
interface LanguageRepository {
    public function findById(int $id): ?LanguageEntity;
    public function findAll(): array;
    public function save(LanguageEntity $language): void;
    public function update(LanguageEntity $language): void;
    public function delete(int $id): void;
}
?>