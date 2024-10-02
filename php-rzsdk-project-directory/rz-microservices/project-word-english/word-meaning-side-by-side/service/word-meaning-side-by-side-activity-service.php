<?php
namespace RzSDK\Service\Activity\Translate\Word\Meaning;
?>
<?php
use RzSDK\Service\Listener\ServiceListener;
use RzSDK\Database\SqliteConnection;
use RzSDK\SqlQueryBuilder\SqlQueryBuilder;
use RzSDK\Model\HTTP\Request\Parameter\Word\Meaning\Entry\RequestWordMeaningEntryQueryModel;
use RzSDK\Model\HTTP\Request\Parameter\Word\Meaning\Side\RequestWordMeaningSideBySideQueryModel;
use RzSDK\Utils\String\StringHelper;
use RzSDK\Database\Space\DbTableListing;
use RzSDK\Model\Database\Word\Entry\DbWordMeaningEntryModel;
use RzSDK\Database\Schema\TblWordMapping;
use RzSDK\Log\DebugLog;
?>
<?php
class WordMeaningSideBySideActivityService implements ServiceListener {
    //
    public ServiceListener $serviceListener;
    public RequestWordMeaningSideBySideQueryModel $wordMeaningSideQueryModel;

    //

    public function __construct(ServiceListener $serviceListener) {
        $this->serviceListener = $serviceListener;
    }

    public function execute(RequestWordMeaningSideBySideQueryModel $wordMeaningSideQueryModel) {
        //DebugLog::log($wordMeaningSideQueryModel);
        $this->wordMeaningSideQueryModel = $wordMeaningSideQueryModel;
        $stringToWordList = $this->toStringToWord($wordMeaningSideQueryModel->source_text, false);
        $removedWordList = $this->toRemovePunctuation($stringToWordList);
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
        $this->wordMeaningSideQueryModel->formatted_text = $retVal;
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
            $retVal = "({$pronunciation} - {$meaning})";
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

    private function toStringToWord($text, $isLowerCase = true) {
        $text = trim($text);
        $text = StringHelper::toSingleSpace($text);
        //
        if(empty($text)) {
            return array();
        }
        $explodeWordList = explode(" ", $text);
        $wordList = array();
        foreach($explodeWordList as $word) {
            $word = trim($word);
            if(!empty($word)) {
                if($isLowerCase) {
                    $word = strtolower($word);
                }
                $wordList[] = $word;
            }
        }
        return $wordList;
    }

    private function toRemovePunctuation($text) {
        if(is_array($text)) {
            $text = implode(" ", $text);
        }
        $text = trim($text);
        $text = StringHelper::toSingleSpace($text);
        //
        $delimiterList = $this->getDelimiterList();
        foreach($delimiterList as $delimiter) {
            $text = str_replace($delimiter, "", $text);
            //$text = preg_replace($delimiter, "", $text);
        }
        $text = $this->toStringToWord($text, false);
        return $text;
    }

    private function getDelimiterList() {
        return array(
            ".", ",", ";", "'", "\"",
            "‘", "’", "❛", "❜", "“", "”",
            "(", ")", "{", "}", "[", "]",
        );
    }
}
?>