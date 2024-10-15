<?php
namespace RzSDK\Service\Activity\Word\Edit;
?>
<?php
use RzSDK\Service\Listener\ServiceListener;
use RzSDK\Model\HTTP\Request\Word\Edit\HttpWordEditRequestModel;
use RzSDK\Database\SqliteConnection;
use RzSDK\SqlQueryBuilder\SqlQueryBuilder;
use RzSDK\DatabaseSpace\DbWordTable;
use RzSDK\Database\Word\DictionaryWord;
use RzSDK\Model\Database\Data\Word\Edit\WordEditDataModule;
use RzSDK\Log\DebugLog;
?>
<?php
class WordEditActivityService {
    //
    public ServiceListener $serviceListener;
    //
    public function __construct(ServiceListener $serviceListener) {
        $this->serviceListener = $serviceListener;
    }

    public function execute(HttpWordEditRequestModel $wordEntryRequestModel) {
        //DebugLog::log($wordEntryRequestModel);
        //
        $tempDictionaryWord = new DictionaryWord();
        $wordIdCol = $tempDictionaryWord->word_id;
        $wordCol = $tempDictionaryWord->word;
        $tempDictionaryWord = null;
        //
        $dbWordEditModel = new WordEditDataModule();
        $updateDataSet = $dbWordEditModel->getWordUpdateDataSet($wordEntryRequestModel);
        //
        $wordWhereDataSet = array(
            $wordIdCol => $wordEntryRequestModel->url_word_id,
        );
        //
        $dictionaryWordTableName = DbWordTable::dictionaryWordWithPrefix();
        //
        $sqlQueryBuilder = new SqlQueryBuilder();
        $sqlQuery = $sqlQueryBuilder
            ->update($dictionaryWordTableName)
            ->set($updateDataSet)
            ->where("", $wordWhereDataSet)
            ->build();
        //DebugLog::log($sqlQuery);
        $dbConn = $this->getDbConnection();
        $dbResult = $this->doRunDatabaseQuery($dbConn, $sqlQuery);
        $dbResult = null;
        $this->serviceListener->onSuccess(null, "Successfully updated.");
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
