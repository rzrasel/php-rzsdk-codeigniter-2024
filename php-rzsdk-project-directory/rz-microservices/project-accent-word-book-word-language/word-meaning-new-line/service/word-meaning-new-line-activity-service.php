<?php
namespace RzSDK\Service\Activity\Translate\Word\Meaning;
?>
<?php
use RzSDK\Service\Listener\ServiceListener;
use RzSDK\Database\SqliteConnection;
use RzSDK\Model\HTTP\Request\Parameter\Word\Meaning\NewLine\RequestWordMeaningNewLineQueryModel;
use RzSDK\Utils\String\StringHelper;
use RzSDK\Database\Space\DbTableListing;
use RzSDK\String\To\Word\Extension\StringToWordExtension;
use RzSDK\Database\Schema\TblWordMapping;
use RzSDK\Log\DebugLog;
?>
<?php
class WordMeaningNewLineActivityService implements ServiceListener {
    //
    public ServiceListener $serviceListener;
    public RequestWordMeaningNewLineQueryModel $wordMeaningNewLineQueryModel;

    //

    public function __construct(ServiceListener $serviceListener) {
        $this->serviceListener = $serviceListener;
    }

    public function execute(RequestWordMeaningNewLineQueryModel $wordMeaningNewLineQueryModel) {
        //DebugLog::log($wordMeaningNewLineQueryModel);
        $this->wordMeaningNewLineQueryModel = $wordMeaningNewLineQueryModel;
        /**/
        $sourceText = $wordMeaningNewLineQueryModel->source_text;
        $lineOfString = $this->toStringToNewLine($sourceText);
        //
        $retVal = "";
        //DebugLog::log($lineOfString);
        foreach($lineOfString as $line) {
            $stringToWordList = StringToWordExtension::toStringToWord($wordMeaningNewLineQueryModel->source_text, false);
            $removedWordList = StringToWordExtension::toRemovePunctuation($stringToWordList);
            //DebugLog::log($removedWordList);
            $value = $this->runTranslateWord($stringToWordList, $removedWordList);
            //DebugLog::log($value);
            //
            $retVal = "{$line}\n{$value[0]}\n{$value[1]}\n{$value[2]}\n";
        }
        $this->wordMeaningNewLineQueryModel->formatted_text = $retVal;
    }

    private function runTranslateWord($mainWordList, $removedWordList) {
        $retVal = array();
        $meaningBoth = "";
        $pronunciation = "";
        $meaning = "";
        for($i = 0; $i < count($removedWordList); $i++) {
            $word = $mainWordList[$i];
            $searchWord = $removedWordList[$i];
            $value = $this->runDatabaseQuery($searchWord);
            if(!empty($value)) {
                $meaningBoth .= "{$value[0]}; ";
                $pronunciation .= "{$value[1]} ";
                $meaning .= "{$value[2]} ";
            }
        }
        $meaningBoth = trim($meaningBoth, "; ");
        $pronunciation = trim($pronunciation);
        $meaning = trim($meaning);
        $retVal[] = $meaningBoth;
        $retVal[] = $pronunciation;
        $retVal[] = $meaning;
        //DebugLog::log($retVal);
        //$this->wordMeaningNewLineQueryModel->formatted_text = $retVal;
        return $retVal;
    }

    private function runDatabaseQuery($word) {
        $sqlQuery = $this->getSearchSql($word);
        $dbConn = $this->getDbConnection();
        $dbResult = $this->doRunDatabaseQuery($dbConn, $sqlQuery);
        if(empty($dbResult)) {
            return array();
        }
        //
        $retVal = array();
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
            $retVal[] = "({$pronunciation} - {$meaning})";
            $retVal[] = $pronunciation;
            $retVal[] = $meaning;
            $counter++;
        }
        $dbResult = null;
        //
        if($counter <= 0) {
            return array();
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

    private function toStringToNewLine($text) {
        $explodeLineList = array();
        $text = str_replace(array("-", "–", "—",), " ", $text);
        $text = StringToWordExtension::toRemovePunctuation($text);
        if(is_array($text)) {
            $text = implode(" ", $text);
        }
        $delimiterList = $this->getNewLineDelimiterList();
        foreach($delimiterList as $delimiter) {
            $explodeLineList = explode($delimiter, $text);
            if(count($explodeLineList) > 1) {
                break;
            }
        }
        //DebugLog::log($explodeLineList);
        return $explodeLineList;
    }

    private function getNewLineDelimiterList() {
        return array(
            ".", "।",
        );
    }
}
?>