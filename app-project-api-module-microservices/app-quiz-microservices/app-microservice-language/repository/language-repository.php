<?php
namespace App\Microservice\Domain\Repository\Language;
?>
<?php
use App\Microservice\Schema\Domain\Model\Language\LanguageEntity;
?>
<?php
interface LanguageRepository {
    public function createLanguage(LanguageEntity $language): void;
    public function updateLanguage(LanguageEntity $language): void;
}
?>