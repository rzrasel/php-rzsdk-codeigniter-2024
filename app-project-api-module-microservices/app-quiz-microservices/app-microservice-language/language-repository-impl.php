<?php
namespace App\Microservice\Data\Repository\Language;
?>
<?php
use App\Microservice\Schema\Data\Model\Language\LanguageModel;
use App\Microservice\Data\Mapper\Language\LanguageMapper;
use App\Microservice\Schema\Domain\Model\Language\LanguageEntity;
use App\Microservice\Domain\Repository\Language\LanguageRepository;
?>
<?php
class LanguageRepositoryImpl implements LanguageRepository {
    private $db;

    public function __construct(Database $db) {
        $this->db = $db->getConnection();
    }

    public function findById(int $id): ?LanguageEntity {
        $stmt = $this->db->prepare("SELECT * FROM tbl_language_data WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $model = $result->fetch_object(LanguageModel::class);
        return $model ? LanguageMapper::toEntity($model) : null;
    }

    public function findAll(): array {
        $result = $this->db->query("SELECT * FROM tbl_language_data");
        $entities = [];
        while ($model = $result->fetch_object(LanguageModel::class)) {
            $entities[] = LanguageMapper::toEntity($model);
        }
        return $entities;
    }

    public function save(LanguageEntity $language): void {
        $stmt = $this->db->prepare("INSERT INTO tbl_language_data (iso_code_2, iso_code_3, name) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $language->getIsoCode2(), $language->getIsoCode3(), $language->getName());
        $stmt->execute();
    }

    public function update(LanguageEntity $language): void {
        $stmt = $this->db->prepare("UPDATE tbl_language_data SET iso_code_2 = ?, iso_code_3 = ?, name = ? WHERE id = ?");
        $stmt->bind_param("sssi", $language->getIsoCode2(), $language->getIsoCode3(), $language->getName(), $language->getId());
        $stmt->execute();
    }

    public function delete(int $id): void {
        $stmt = $this->db->prepare("DELETE FROM tbl_language_data WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
    }
}
?>