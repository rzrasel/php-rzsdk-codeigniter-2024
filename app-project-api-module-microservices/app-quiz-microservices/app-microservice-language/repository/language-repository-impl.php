<?php
namespace App\Microservice\Data\Repository\Language;
?>
<?php
use App\Microservice\Schema\Data\Model\Language\LanguageModel;
use App\Microservice\Data\Mapper\Language\LanguageMapper;
use App\Microservice\Schema\Domain\Model\Language\LanguageEntity;
use App\Microservice\Domain\Repository\Language\LanguageRepository;
use App\Microservice\Core\Utils\Database\Database;
use App\Microservice\Core\Utils\Type\Response\ResponseStatus;
use App\Microservice\Core\Utils\Data\Inner\Data\Bus\InnerDataBus;
use RzSDK\Database\SqliteFetchType;
use RzSDK\Log\DebugLog;
?>
<?php
class LanguageRepositoryImpl implements LanguageRepository {
    private $dbConn;
    public static $languageTableName = "tbl_language_data";

    public function __construct() {
        //$database = new Database();
        $this->dbConn = (new Database())->getConnection();
    }

    public function createLanguage(LanguageEntity $language): InnerDataBus {
        /*$stmt = $this->db->prepare("INSERT INTO tbl_language_data (iso_code_2, iso_code_3, name) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $language->getIsoCode2(), $language->getIsoCode3(), $language->getName());
        $stmt->execute();*/
        //|Create a temporary LanguageEntity instance to access column names
        $tempLanguageEntity = new LanguageEntity();
        $languageTableName = self::$languageTableName;

        //|Define column names and their corresponding values|---|
        $colLanguageName = $tempLanguageEntity->name;
        $colLanguageNameValue = $language->name;
        $colLanguageSlug = $tempLanguageEntity->slug;
        $colLanguageSlugValue = $language->slug;
        /*if($this->dbConn->isDataExists($columnTableName, $colIdName, $colIdValue)) {
            //$this->save($columnData, $colIdValue);
            DebugLog::log("Data already exists");
            return;
        }*/
        //|Check if a language with the same name already exists
        $sqlQuery = "SELECT * FROM {$languageTableName} WHERE {$colLanguageName} = '{$colLanguageNameValue}';";
        //echo $sqlQuery;
        $dbResult = $this->dbConn->query($sqlQuery, SqliteFetchType::FETCH_OBJ);
        //DebugLog::log($dbResult);
        //print_r($dbResult);
        //|If the name exists, return a response indicating duplication
        if(!empty($dbResult)) {
            return $this->getResponse(
                "'{$language->name}' language name already exists.",
                false,
                $language,
            );
        }
        //|Check if a language with the same slug already exists
        $sqlQuery = "SELECT * FROM {$languageTableName} WHERE {$colLanguageSlug} = '{$colLanguageSlugValue}';";
        //echo $sqlQuery;
        $dbResult = $this->dbConn->query($sqlQuery, SqliteFetchType::FETCH_OBJ);
        //DebugLog::log($dbResult);
        //print_r($dbResult);
        //|If the slug exists, return a response indicating duplication
        if(!empty($dbResult)) {
            return $this->getResponse(
                "'{$language->slug}' language slug already exists.",
                false,
                $language,
            );
        }
        $dataVarList = $tempLanguageEntity->getVarList();
        $columns = "";
        $values = "";
        foreach($dataVarList as $var) {
            $columns .= "$var, ";
            $values .= ":$var, ";
        }
        $columns = trim(trim($columns), ",");
        $values = trim(trim($values), ",");
        $sqlQuery = "INSERT INTO {$languageTableName} ($columns) VALUES ($values)";
        //DebugLog::log($sqlQuery);
        $params = self::getParams($language);
        //print_r($params);
        $this->dbConn->execute($sqlQuery, $params);
        //|Return a success response|----------------------------|
        return $this->getResponse(
            "Successfully '{$language->name}' language created.",
            true,
            $language,
        );
    }

    public function updateLanguage(LanguageEntity $language): void {
        /*$stmt = $this->db->prepare("UPDATE tbl_language_data SET iso_code_2 = ?, iso_code_3 = ?, name = ? WHERE id = ?");
        $stmt->bind_param("sssi", $language->getIsoCode2(), $language->getIsoCode3(), $language->getName(), $language->getId());
        $stmt->execute();*/
    }

    /*public function deleteLanguage(int $id): void {
        $stmt = $this->db->prepare("DELETE FROM tbl_language_data WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
    }*/

    public static function getParams(LanguageEntity $schema): array {
        $params = [];
        $dataList = $schema->getVarListWithKey();
        foreach($dataList as $key => $value) {
            $params[":$key"] = $value;
        }
        return $params;
    }

    private function getResponse(string $message, bool $status, $data = null): InnerDataBus {
        return new InnerDataBus($message, $status, $data);
    }
}
?>