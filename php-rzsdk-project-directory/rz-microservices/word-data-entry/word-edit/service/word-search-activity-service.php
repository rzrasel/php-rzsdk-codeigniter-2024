<?php
namespace RzSDK\Service\Word\Edit\Activity\Search;
?>
<?php
use RzSDK\Database\SqliteConnection;
use RzSDK\Utils\Database\Options\Language\DatabaseLanguageOptions;
use RzSDK\Log\DebugLog;
?>
<?php
class WordSearchActivityService {

    public function getWordLanguageOptions($languageId = "", $defaultLanguage = "English") {
        return (new DatabaseLanguageOptions())
            ->getLanguageOptions($languageId, $defaultLanguage);
    }

    public function getMeaningLanguageOptions($languageId, $defaultLanguage = "Bangla") {
        return (new DatabaseLanguageOptions())
            ->getLanguageOptions($languageId, $defaultLanguage);
    }

    private function doRunDatabaseQuery($dbConn, $sqlQuery) {
        return $dbConn->query($sqlQuery);
    }

    private function getDbConnection() {
        $dbFullPath = "../" . DB_PATH . "/" . DB_FILE;
        return new SqliteConnection($dbFullPath);
    }
}
?>