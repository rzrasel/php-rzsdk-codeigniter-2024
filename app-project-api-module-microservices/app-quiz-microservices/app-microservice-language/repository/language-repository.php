<?php
namespace App\Microservice\Domain\Repository\Language;
?>
<?php
use App\Microservice\Schema\Domain\Model\Language\LanguageEntity;
use App\Microservice\Core\Utils\Data\Inner\Data\Bus\InnerDataBus;
?>
<?php
interface LanguageRepository {
    public function createLanguage(LanguageEntity $language): InnerDataBus;
    public function updateLanguage(LanguageEntity $language): void;
}
?>