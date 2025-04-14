<?php
namespace App\Microservice\Data\Repository\Subject;
?>
<?php
use App\Microservice\Schema\Data\Model\Language\LanguageModel;
use App\Microservice\Data\Mapper\Language\LanguageMapper;
use App\Microservice\Schema\Domain\Model\Subject\SubjectEntity;
use App\Microservice\Schema\Domain\Model\Language\LanguageEntity;
use App\Microservice\Domain\Repository\Subject\SubjectRepository;
use App\Microservice\Core\Utils\Database\Database;
use App\Microservice\Core\Utils\Type\Response\ResponseStatus;
use App\Microservice\Core\Utils\Data\Inner\Data\Bus\InnerDataBus;
use RzSDK\Database\SqliteFetchType;
use RzSDK\Log\DebugLog;
?>
<?php
class SubjectRepositoryImpl implements SubjectRepository {
    private $dbConn;
    public static $subjectTableName = "tbl_subject_data";

    public function __construct() {
        //$database = new Database();
        $this->dbConn = (new Database())->getConnection();
    }

    public function createLanguage(SubjectEntity $subject): InnerDataBus {
        $tempSubjectEntity = new SubjectEntity();
        $subjectTableName = self::$subjectTableName;
        /*$colSubjectName = $tempSubjectEntity->name;
        $colSubjectNameValue = $subject->name;
        $colSubjectSlug = $tempSubjectEntity->slug;
        $colSubjectSlugValue = $subject->slug;
        //
        $sqlQuery = "SELECT * FROM {$subjectTableName} WHERE {$colSubjectName} = '{$colSubjectNameValue}';";
        //echo $sqlQuery;
        $dbResult = $this->dbConn->query($sqlQuery, SqliteFetchType::FETCH_OBJ);
        //DebugLog::log($dbResult);
        //print_r($dbResult);
        if(!empty($dbResult)) {
            return $this->getResponse(
                "'{$subject->name}' language name already exists.",
                false,
                $language,
            );
        }
        $sqlQuery = "SELECT * FROM {$languageTableName} WHERE {$colLanSlug} = '{$colLanSlugValue}';";
        //echo $sqlQuery;
        $dbResult = $this->dbConn->query($sqlQuery, SqliteFetchType::FETCH_OBJ);
        //DebugLog::log($dbResult);
        //print_r($dbResult);
        if(!empty($dbResult)) {
            return $this->getResponse(
                "'{$language->slug}' language slug already exists.",
                false,
                $language,
            );
        }*/
        $dataVarList = $tempSubjectEntity->getVarList();
        $columns = implode(", ", $dataVarList);
        $placeholders = ":" . implode(", :", $dataVarList);

        $sqlQuery = "INSERT INTO {$subjectTableName} ($columns) VALUES ($placeholders)";
        //DebugLog::log($sqlQuery);
        $params = self::getParams($subject);
        print_r($params);
        //$this->dbConn->execute($sqlQuery, $params);
        return $this->getResponse(
            "Successfully '{$subject->name}' subject created.",
            true,
            $subject,
        );
    }

    public function updateLanguage(SubjectEntity $subject): void {
        /*$stmt = $this->db->prepare("UPDATE tbl_language_data SET iso_code_2 = ?, iso_code_3 = ?, name = ? WHERE id = ?");
        $stmt->bind_param("sssi", $language->getIsoCode2(), $language->getIsoCode3(), $language->getName(), $language->getId());
        $stmt->execute();*/
    }

    /*public function deleteLanguage(int $id): void {
        $stmt = $this->db->prepare("DELETE FROM tbl_language_data WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
    }*/

    /*public static function getParams(SubjectEntity $schema): array {
        $params = [];
        $dataList = $schema->getVarListWithKey();
        foreach($dataList as $key => $value) {
            $params[":$key"] = $value;
        }
        return $params;
    }*/

    public static function getParams(SubjectEntity $schema): array {
        return array_combine(
            array_map(function($key) { return ":$key"; }, array_keys($schema->getVarListWithKey())),
            array_values($schema->getVarListWithKey())
        );
    }

    private function getResponse(string $message, bool $status, $data = null): InnerDataBus {
        return new InnerDataBus($message, $status, $data);
    }
}
?>