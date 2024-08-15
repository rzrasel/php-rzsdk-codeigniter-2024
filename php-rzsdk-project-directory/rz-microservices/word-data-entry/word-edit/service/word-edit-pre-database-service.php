<?php
namespace RzSDK\Service\Word\Edit\Database\Preposition;
?>
<?php
use RzSDK\Database\SqliteConnection;
use RzSDK\Service\Listener\ServiceListener;
use RzSDK\Model\HTTP\Request\Word\Edit\HttpWordEditRequestModel;
use RzSDK\Database\Word\DictionaryWord;
use RzSDK\DatabaseSpace\DbWordTable;
use RzSDK\SqlQueryBuilder\SqlQueryBuilder;
use RzSDK\Log\DebugLog;
?>
<?php
class WordEditPreDatabaseService {
    //
    public ServiceListener $serviceListener;
    public HttpWordEditRequestModel $wordEditRequestModel;
    //
    public function __construct(ServiceListener $serviceListener) {
        $this->serviceListener = $serviceListener;
    }

    public function execute(HttpWordEditRequestModel $wordEntryRequestModel) {
        //DebugLog::log($wordEntryRequestModel);
        $tempDictionaryWord = new DictionaryWord();
        $wordCol = $tempDictionaryWord->word;
        $pronunciationCol = $tempDictionaryWord->pronunciation;
        $accentUSCol = $tempDictionaryWord->accent_us;
        $accentUKCol = $tempDictionaryWord->accent_uk;
        $partsOfSpeechCol = $tempDictionaryWord->parts_of_speech;
        $syllableCol = $tempDictionaryWord->syllable;
        $tempDictionaryWord = null;
        $wordEntryRequestModel->word = ucwords(strtolower($wordEntryRequestModel->word));
        //
        $dictionaryWordTableName = DbWordTable::dictionaryWordWithPrefix();
        $sqlQueryBuilder = new SqlQueryBuilder();
        $sqlQuery = $sqlQueryBuilder
            ->select()
            ->from($dictionaryWordTableName)
            ->where("", array(
                $wordCol => $wordEntryRequestModel->word,
            ))
            ->build();
        //DebugLog::log($sqlQuery);
        $dbConn = $this->getDbConnection();
        $dbResult = $this->doRunDatabaseQuery($dbConn, $sqlQuery);
        if(!empty($dbResult)) {
            $counter = 0;
            foreach($dbResult as $row) {
                $counter++;
                $wordEntryRequestModel->pronunciation = $row[$pronunciationCol];
                $wordEntryRequestModel->accent_us = $row[$accentUSCol];
                $wordEntryRequestModel->accent_uk = $row[$accentUKCol];
                $wordEntryRequestModel->parts_of_speech = $row[$partsOfSpeechCol];
                $wordEntryRequestModel->syllable = $row[$syllableCol];
            }
            //DebugLog::log($counter);
        }
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
