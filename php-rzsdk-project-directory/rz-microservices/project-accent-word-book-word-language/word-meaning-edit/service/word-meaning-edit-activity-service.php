<?php
namespace RzSDK\Service\Activity\Edit\Word\Meaning;
?>
    <?php
use RzSDK\Service\Listener\ServiceListener;
use RzSDK\Database\SqliteConnection;
use RzSDK\SqlQueryBuilder\SqlQueryBuilder;
use RzSDK\Utils\String\StringHelper;
use RzSDK\Database\Space\DbTableListing;
use RzSDK\Model\Database\Word\Entry\DbWordMeaningEntryModel;
use RzSDK\Database\Schema\TblWordMapping;
use RzSDK\Model\HTTP\Request\Parameter\Word\Meaning\Edit\RequestWordMeaningEditQueryModel;
use RzSDK\Model\Database\Word\Edit\DbWordMeaningEditModel;
use RzSDK\Log\DebugLog;
?>
<?php
class WordMeaningEditActivityService implements ServiceListener {
    //
    public ServiceListener $serviceListener;
    public RequestWordMeaningEditQueryModel $wordMeaningEditQueryModel;
    //

    public function __construct(ServiceListener $serviceListener) {
        $this->serviceListener = $serviceListener;
    }

    public function execute(RequestWordMeaningEditQueryModel $wordMeaningEditQueryModel) {
        $this->wordMeaningEditQueryModel = $wordMeaningEditQueryModel;
        //DebugLog::log($this->wordMeaningEditQueryModel);
        $this->isWordExistsInOtherId();
    }

    private function isWordExistsInOtherId() {
        //
        $tempTblWordMapping = new TblWordMapping();
        $colWordId = $tempTblWordMapping->word_id;
        $colWord = $tempTblWordMapping->word;
        $colPronunciation = $tempTblWordMapping->pronunciation;
        $colMeaning = $tempTblWordMapping->meaning;
        $tempTblWordMapping = null;
        //
        $wordId = $this->wordMeaningEditQueryModel->word_id;
        $word = $this->wordMeaningEditQueryModel->word;
        $word = StringHelper::toSingleSpace($word);
        $word = strtolower($word);
        //
        $wordMappingTableName = DbTableListing::wordMappingWithPrefix();
        //
        $sqlQuery = "SELECT * FROM {$wordMappingTableName} "
            . " WHERE"
            . " {$colWord} = '{$word}'"
            . " AND {$colWordId} <> '{$wordId}'"
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
            $this->onError(null, "Error! Word \"{$this->wordMeaningEditQueryModel->word}\" already exist");
            return;
        }
        //
        $this->runWordDbUpdate();
        //
    }

    private function runWordDbUpdate() {
        //
        $tempTblWordMapping = new TblWordMapping();
        $colWordId = $tempTblWordMapping->word_id;
        $colWord = $tempTblWordMapping->word;
        $colPronunciation = $tempTblWordMapping->pronunciation;
        $colMeaning = $tempTblWordMapping->meaning;
        $tempTblWordMapping = null;
        //
        $dbWordMeaningEditModel = new DbWordMeaningEditModel();
        $updateDataSet = $dbWordMeaningEditModel->getWordMeaningUpdateDataSet($this->wordMeaningEditQueryModel);
        //DebugLog::log($updateDataSet);
        //
        $wordWhereDataSet = array(
            $colWordId => $this->wordMeaningEditQueryModel->word_id,
        );
        //
        $wordMappingTableName = DbTableListing::wordMappingWithPrefix();
        //
        $sqlQueryBuilder = new SqlQueryBuilder();
        $sqlQuery = $sqlQueryBuilder
            ->update($wordMappingTableName)
            ->set($updateDataSet)
            ->where("", $wordWhereDataSet)
            ->build();
        //DebugLog::log($sqlQuery);
        //
        $dbConn = $this->getDbConnection();
        $dbResult = $this->doRunDatabaseQuery($dbConn, $sqlQuery);
        $dbResult = null;
        //
        $this->onSuccess($this->wordMeaningEditQueryModel, "Successfully updated \"{$this->wordMeaningEditQueryModel->word}\"");
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