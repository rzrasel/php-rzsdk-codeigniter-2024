<?php
namespace RzSDK\Service\Activity\Translate\Word\Meaning;
?>
<?php
use RzSDK\Service\Listener\ServiceListener;
use RzSDK\Database\SqliteConnection;
use RzSDK\Utils\String\StringHelper;
use RzSDK\Database\Space\DbTableListing;
use RzSDK\String\To\Word\Extension\StringToWordExtension;
use RzSDK\Database\Schema\TblWordMapping;
use RzSDK\Model\HTTP\Request\Parameter\Word\Meaning\Pronunciation\RequestWordMeaningPronunciationQueryModel;
use RzSDK\Log\DebugLog;
?>
<?php
class WordMeaningPronunciationActivityService implements ServiceListener {
    //
    public ServiceListener $serviceListener;
    public RequestWordMeaningPronunciationQueryModel $wordMeaningPronunciationQueryModel;

    //

    public function __construct(ServiceListener $serviceListener) {
        $this->serviceListener = $serviceListener;
    }

    public function execute(RequestWordMeaningPronunciationQueryModel $wordMeaningPronunciationQueryModel) {
        //DebugLog::log($wordMeaningSideQueryModel);
        $this->wordMeaningPronunciationQueryModel = $wordMeaningPronunciationQueryModel;
        $stringToWordList = StringToWordExtension::toStringToWord($wordMeaningPronunciationQueryModel->source_text, false);
        $removedWordList = StringToWordExtension::toRemovePunctuation($stringToWordList);
        //DebugLog::log($removedWordList);
        $this->runTranslateWord($stringToWordList, $removedWordList);
    }

    private function runTranslateWord($mainWordList, $removedWordList) {
        $retVal = "";
        for($i = 0; $i < count($removedWordList); $i++) {
            $word = $mainWordList[$i];
            $searchWord = $removedWordList[$i];
            $retVal .= "{$word} {$this->runDatabaseQuery($searchWord)} ";
        }
        $retVal = trim($retVal);
        //DebugLog::log($retVal);
        $this->wordMeaningPronunciationQueryModel->formatted_text = $retVal;
    }

    private function runDatabaseQuery($word) {
        $sqlQuery = $this->getSearchSql($word);
        $dbConn = $this->getDbConnection();
        $dbResult = $this->doRunDatabaseQuery($dbConn, $sqlQuery);
        if(empty($dbResult)) {
            return "";
        }
        //
        $retVal = "";
        //
        $tempTblWordMapping = new TblWordMapping();
        $colWordId = $tempTblWordMapping->word_id;
        $colWord = $tempTblWordMapping->word;
        $colPronunciation = $tempTblWordMapping->pronunciation;
        $colMeaning = $tempTblWordMapping->meaning;
        $tempTblWordMapping = null;
        //
        //
        $counter = 0;
        foreach($dbResult as $row) {
            //DebugLog::log($row);
            $wordId = $row[$colWordId];
            $word = $row[$colWord];
            $pronunciation = $row[$colPronunciation];
            $meaning = $row[$colMeaning];
            $retVal = "({$pronunciation})";
            $counter++;
        }
        $dbResult = null;
        //
        if($counter <= 0) {
            return "";
        }
        return $retVal;
    }

    private function getSearchSql($word) {
        //
        $tempTblWordMapping = new TblWordMapping();
        $colWordId = $tempTblWordMapping->word_id;
        $colWord = $tempTblWordMapping->word;
        $colPronunciation = $tempTblWordMapping->pronunciation;
        $colMeaning = $tempTblWordMapping->meaning;
        $tempTblWordMapping = null;
        //
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
        return $sqlQuery;
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