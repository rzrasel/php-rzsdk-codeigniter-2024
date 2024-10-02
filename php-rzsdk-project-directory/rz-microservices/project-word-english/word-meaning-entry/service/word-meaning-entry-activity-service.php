<?php
namespace RzSDK\Service\Activity\Entry\Word\Meaning;
?>
<?php
use RzSDK\Service\Listener\ServiceListener;
use RzSDK\Database\SqliteConnection;
use RzSDK\SqlQueryBuilder\SqlQueryBuilder;
use RzSDK\Model\HTTP\Request\Parameter\Word\Meaning\Entry\RequestWordMeaningEntryQueryModel;
use RzSDK\Utils\String\StringHelper;
use RzSDK\Database\Space\DbTableListing;
use RzSDK\Model\Database\Word\Entry\DbWordMeaningEntryModel;
use RzSDK\Database\Schema\TblWordMapping;
use RzSDK\Log\DebugLog;
?>
<?php
class WordMeaningEntryActivityService implements ServiceListener {
    //
    public ServiceListener $serviceListener;
    public RequestWordMeaningEntryQueryModel $wordMeaningEntryQueryModel;
    //

    public function __construct(ServiceListener $serviceListener) {
        $this->serviceListener = $serviceListener;
    }

    public function execute(RequestWordMeaningEntryQueryModel $wordMeaningEntryQueryModel) {
        $this->wordMeaningEntryQueryModel = $wordMeaningEntryQueryModel;
        //DebugLog::log($this->wordMeaningEntryQueryModel);
        $this->isWordExists();
    }

    private function isWordExists() {
        //
        $tempTblWordMapping = new TblWordMapping();
        $colWordId = $tempTblWordMapping->word_id;
        $colWord = $tempTblWordMapping->word;
        $colPronunciation = $tempTblWordMapping->pronunciation;
        $colMeaning = $tempTblWordMapping->meaning;
        $tempTblWordMapping = null;
        //
        $word = $this->wordMeaningEntryQueryModel->word;
        $word = StringHelper::toSingleSpace($word);
        $word = strtolower($word);
        //
        $wordMappingTableName = DbTableListing::wordMappingWithPrefix();
        //
        $sqlQuery = "SELECT * FROM {$wordMappingTableName} "
            . " WHERE"
            . " {$colWord} = '{$word}'"
            . ";";
        $sqlQuery = StringHelper::toSingleSpace($sqlQuery);
        //DebugLog::log($sqlQuery);
        $dbConn = $this->getDbConnection();
        $dbResult = $this->doRunDatabaseQuery($dbConn, $sqlQuery);
        if(empty($dbResult)) {
            $this->onError(null, "Error! Database error");
            return;
        }
        //
        $counter = 0;
        foreach($dbResult as $row) {
            //DebugLog::log($row);
            $counter++;
        }
        $dbResult = null;
        //
        if($counter > 0) {
            $this->onError(null, "Error! Word \"{$this->wordMeaningEntryQueryModel->word}\" already exist");
            return;
        }
        //
        $this->runWordDbInsert();
        //
    }

    private function runWordDbInsert() {
        $dbWordMeaningEntryModel = new DbWordMeaningEntryModel();
        $insertDataSet = $dbWordMeaningEntryModel->getWordInsertDataSet($this->wordMeaningEntryQueryModel);
        $insertDataSet = $insertDataSet->getColumnWithKey();
        //DebugLog::log($insertDataSet);
        //
        $wordMappingTableName = DbTableListing::wordMappingWithPrefix();
        $sqlQueryBuilder = new SqlQueryBuilder();
        $sqlQuery = $sqlQueryBuilder->insert($wordMappingTableName)
            ->values($insertDataSet)
            ->build();
        //DebugLog::log($sqlQuery);
        $dbConn = $this->getDbConnection();
        $dbResult = $this->doRunDatabaseQuery($dbConn, $sqlQuery);
        $dbResult = null;
        //
        $this->onSuccess($this->wordMeaningEntryQueryModel, "Successfully inserted \"{$this->wordMeaningEntryQueryModel->word}\"");
    }

    private function doRunDatabaseQuery($dbConn, $sqlQuery) {
        return $dbConn->query($sqlQuery);
    }

    private function getDbConnection() {
        $dbFullPath = "../" . DB_PATH . "/" . DB_FILE;
        return new SqliteConnection($dbFullPath);
    }

    public function onError($dataSet, $message) {
        /*DebugLog::log($dataSet);
        DebugLog::log($message);*/
        $this->serviceListener->onError($dataSet, $message);
    }

    public function onSuccess($dataSet, $message) {
        /*DebugLog::log($dataSet);
        DebugLog::log($message);*/
        $this->serviceListener->onSuccess($dataSet, $message);
    }
}
?>