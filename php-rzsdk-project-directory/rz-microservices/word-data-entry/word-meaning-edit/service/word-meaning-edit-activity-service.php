<?php
namespace RzSDK\Service\Word\Meaning\Edit\Activity\Search;
?>
<?php
use RzSDK\Database\SqliteConnection;
use RzSDK\Utils\Database\Options\Language\DatabaseLanguageOptions;
use RzSDK\Service\Listener\ServiceListener;
use RzSDK\Shared\HTTP\Request\Parameter\RequestWordMeaningEditQueryModel;
use RzSDK\Database\Word\Meaning\DictionaryWordMeaning;
use RzSDK\Model\Database\Data\Word\Meaning\Edit\WordMeaningEditDataModule;
use RzSDK\DatabaseSpace\DbWordTable;
use RzSDK\SqlQueryBuilder\SqlQueryBuilder;
use RzSDK\Log\DebugLog;
?>
<?php
class WordMeaningEditActivityService {
    //
    public ServiceListener $serviceListener;
    //

    public function __construct(ServiceListener $serviceListener) {
        $this->serviceListener = $serviceListener;
    }

    public function execute(RequestWordMeaningEditQueryModel $wordMeaningEditQueryModel) {
        //DebugLog::log($wordMeaningEditQueryModel);
        //
        $tempDictionaryWordMeaning = new DictionaryWordMeaning();
        $colLanId = $tempDictionaryWordMeaning->lan_id;
        $colWordId = $tempDictionaryWordMeaning->word_id;
        $colMeaningId = $tempDictionaryWordMeaning->meaning_id;
        $colMeaning = $tempDictionaryWordMeaning->meaning;
        $tempDictionaryWord = null;
        //
        $dbWordMeaningEditModel = new WordMeaningEditDataModule();
        $updateDataSet = $dbWordMeaningEditModel->getWordMeaningUpdateDataSet($wordMeaningEditQueryModel);
        //
        $wordWhereDataSet = array(
            $colMeaningId => $wordMeaningEditQueryModel->meaning_id,
        );
        //
        $tblDictionaryWordMeaning = DbWordTable::dictionaryWordMeaningWithPrefix();
        $sqlQueryBuilder = new SqlQueryBuilder();
        $sqlQuery = $sqlQueryBuilder
            ->update($tblDictionaryWordMeaning)
            ->set($updateDataSet)
            ->where("", $wordWhereDataSet)
            ->build();
        //DebugLog::log($sqlQuery);
        $dbConn = $this->getDbConnection();
        $dbResult = $this->doRunDatabaseQuery($dbConn, $sqlQuery);
        $dbResult = null;
        $this->serviceListener->onSuccess(null, "Successfully updated.");
        //
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