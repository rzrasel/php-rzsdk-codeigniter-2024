<?php
namespace App\Microservice\Data\Repository\Language;
?>
<?php
use App\Microservice\Schema\Data\Model\Language\LanguageModel;
use App\Microservice\Data\Mapper\Language\LanguageMapper;
use App\Microservice\Schema\Domain\Model\Language\LanguageEntity;
use App\Microservice\Domain\Repository\Language\LanguageRepository;
use App\Microservice\Core\Utils\Database\Database;
?>
<?php
class LanguageRepositoryImpl implements LanguageRepository {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function createLanguage(LanguageEntity $language): void {
        $stmt = $this->db->prepare("INSERT INTO tbl_language_data (iso_code_2, iso_code_3, name) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $language->getIsoCode2(), $language->getIsoCode3(), $language->getName());
        $stmt->execute();
    }

    public function updateLanguage(LanguageEntity $language): void {
        $stmt = $this->db->prepare("UPDATE tbl_language_data SET iso_code_2 = ?, iso_code_3 = ?, name = ? WHERE id = ?");
        $stmt->bind_param("sssi", $language->getIsoCode2(), $language->getIsoCode3(), $language->getName(), $language->getId());
        $stmt->execute();
    }

    /*public function deleteLanguage(int $id): void {
        $stmt = $this->db->prepare("DELETE FROM tbl_language_data WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
    }*/
}
?>