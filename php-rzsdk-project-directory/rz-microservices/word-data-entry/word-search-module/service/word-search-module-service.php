<?php
namespace RzSDK\Service\Dictionary\Search\Module;
?>
<?php
use RzSDK\Service\Listener\ServiceListener;
use RzSDK\Model\HTTP\Request\Search\HttpWordSearchModuleRequestModel;
use RzSDK\Model\HTTP\Response\Search\HttpWordSearchModuleResponseModel;
use RzSDK\Database\Word\DictionaryWord;
use RzSDK\DatabaseSpace\DbWordTable;
use RzSDK\SqlQueryBuilder\SqlQueryBuilder;
use RzSDK\Database\SqliteConnection;
use RzSDK\Database\Word\Meaning\DictionaryWordMeaning;
use RzSDK\Model\Url\Word\Parameter\UrlWordParameter;
use RzSDK\Model\Url\Word\Meaning\Parameter\UrlWordMeaningParameter;
use RzSDK\SqlQueryBuilder\SqlOrderType;
use RzSDK\Log\DebugLog;
?>
<?php
class WordSearchModuleService {
    //
    public ServiceListener $serviceListener;
    public HttpWordSearchModuleResponseModel $searchModuleResponseModel;
    //
    public function __construct(ServiceListener $serviceListener) {
        $this->serviceListener = $serviceListener;
        $this->searchModuleResponseModel = new HttpWordSearchModuleResponseModel();
        $this->searchModuleResponseModel->data = null;
        $this->searchModuleResponseModel->is_error = true;
        $this->searchModuleResponseModel->message = "Unknown error";
    }

    public function execute(HttpWordSearchModuleRequestModel $searchModuleRequestModel) {
        //DebugLog::log($searchModuleRequestModel);
        /*$this->searchModuleResponseModel->data = $searchModuleRequestModel;
        $this->searchModuleResponseModel->is_error = false;
        $this->searchModuleResponseModel->message = "Error! data empty";
        $this->serviceListener->onSuccess($this->searchModuleResponseModel, $this->searchModuleResponseModel->message);*/
        //
        $tempDictionaryWord = new DictionaryWord();
        $colLanId = $tempDictionaryWord->lan_id;
        $colWordId = $tempDictionaryWord->word_id;
        $colWord = $tempDictionaryWord->word;
        $colPronunciation = $tempDictionaryWord->pronunciation;
        $colPartsOfSpeech = $tempDictionaryWord->parts_of_speech;
        $colSyllable = $tempDictionaryWord->syllable;
        $colWordCreateDate = $tempDictionaryWord->created_date;
        $tempDictionaryWord = null;
        //
        $tempDictionaryWordMeaning = new DictionaryWordMeaning();
        $colMeaningWordId = $tempDictionaryWordMeaning->word_id;
        $tempDictionaryWordMeaning = null;
        //
        $tblDictionaryWord = DbWordTable::dictionaryWordWithPrefix();
        $dictionaryWordMeaningTableName = DbWordTable::dictionaryWordMeaningWithPrefix();
        $sqlQueryBuilder = new SqlQueryBuilder();
        $sqlQuery = $sqlQueryBuilder
            ->select()
            ->from($tblDictionaryWord)
            //->innerJoin($dictionaryWordTableName, $dictionaryWordMeaningTableName, $wordIdCol, $meaningWordIdCol)
            //->leftJoin($dictionaryWordTableName, $dictionaryWordMeaningTableName, $wordIdCol, $meaningWordIdCol)
            /*->where($dictionaryWordTableName, array(
                //$wordCol => $searchModuleRequestModel->word,
                "{$wordCol} LIKE '{$searchModuleRequestModel->search_word}%'",
            ))*/
            ->where($tblDictionaryWord, array(
                //$wordCol => $searchModuleRequestModel->word,
                $colWord => "{LIKE}{$searchModuleRequestModel->search_word}%",
                $colLanId => $searchModuleRequestModel->url_word_language,
            ))
            /*->where($dictionaryWordTableName, array(
                $lanIdCol => $searchModuleRequestModel->word_language,
            ))*/
            ->orderByWithTable($tblDictionaryWord, $colWord, SqlOrderType::ASC)
            ->orderByWithTable($tblDictionaryWord, $colWordCreateDate, SqlOrderType::DESC)
            ->limit($searchModuleRequestModel->limit)
            ->offset(0)
            ->build();
        //DebugLog::log($sqlQuery);
        $dbConn = $this->getDbConnection();
        $dbResult = $this->doRunDatabaseQuery($dbConn, $sqlQuery);
        if(empty($dbResult)) {
            $this->searchModuleResponseModel->data = $searchModuleRequestModel;
            $this->searchModuleResponseModel->is_error = true;
            $this->searchModuleResponseModel->message = "Error! database data error";
            $this->serviceListener->onError($this->searchModuleResponseModel, $this->searchModuleResponseModel->message);
            return;
        }
        //
        $retVal = $this->getHtmlTableTop();
        $counter = 0;
        foreach($dbResult as $row) {
            //DebugLog::log($row);
            $counter++;
            //
            $wordId = $row[$colWordId];
            $word = $row[$colWord];
            //
            $urlWordParameter = new UrlWordParameter();
            $urlWordParameter->url_word_language = $searchModuleRequestModel->url_word_language;
            $urlWordParameter->url_word_id = $wordId;
            $urlWordParameter->url_word = $word;
            $urlWordParameter->search_word = $searchModuleRequestModel->search_word;
            //
            //DebugLog::log($wordQueryParameter);
            $wordMeaningDataSet = $this->getWordMeaningDatabaseDataSet($dbConn, $urlWordParameter, $searchModuleRequestModel);
            //
            $wordUrlLink = "{$searchModuleRequestModel->word_link_url}?{$urlWordParameter->toQueryParameter()}";
            //url_word_id
            //$urlSelf = urlencode($urlSelf);
            $retVal .= "<tr>\n"
                . "<td style=\"padding-left: 10px;\">{$counter}</td>\n"
                . "<td><a href=\"{$wordUrlLink}\" target=\"_self\">{$word}</a></td>\n"
                //. "<td><a href=\"{$searchModuleRequestModel->word_link_url}?word_language={$searchModuleRequestModel->language}&url_word={$row["word"]}&url_word_id={$row["word_id"]}\" target=\"_blank\" >{$row["word"]}</a></td>\n"
                . "<td>{$row[$colPronunciation]}</td>\n"
                . "<td>{$row[$colPartsOfSpeech]}</td>\n"
                . "<td>{$row[$colSyllable]}</td>\n"
                . "<td id=\"word-meaning-column\" style=\"padding-right: 10px;\" class=\"word-meaning-column\">{$wordMeaningDataSet}</td>\n"
                . "</tr>\n"
                . " ";
        }
        if($counter < 1) {
            $this->searchModuleResponseModel->data = $searchModuleRequestModel;
            $this->searchModuleResponseModel->is_error = true;
            $this->searchModuleResponseModel->message = "\"{$searchModuleRequestModel->search_word}\" word not found in dictionary";
            $this->serviceListener->onError($this->searchModuleResponseModel, $this->searchModuleResponseModel->message);
            return;
        }
        //
        $retVal = $retVal
            . "</tbody>\n"
            . "</table>\n"
            . " ";
        $this->searchModuleResponseModel->data = $retVal;
        $this->searchModuleResponseModel->is_error = false;
        $this->searchModuleResponseModel->message = "Successfully data found";
        $this->serviceListener->onSuccess($this->searchModuleResponseModel, $this->searchModuleResponseModel->message);
    }

    private function getWordMeaningDatabaseDataSet($dbConn, UrlWordParameter $urlWordParameter, HttpWordSearchModuleRequestModel $searchModuleRequestModel) {
        //
        //DebugLog::log($searchModuleRequestModel);
        //
        $tempDictionaryWordMeaning = new DictionaryWordMeaning();
        $colLanId = $tempDictionaryWordMeaning->lan_id;
        $colWordId = $tempDictionaryWordMeaning->word_id;
        $colMeaningId = $tempDictionaryWordMeaning->meaning_id;
        $colMeaning = $tempDictionaryWordMeaning->meaning;
        $tempDictionaryWordMeaning = null;
        //
        $tblDictionaryWordMeaning = DbWordTable::dictionaryWordMeaningWithPrefix();
        $sqlQueryBuilder = new SqlQueryBuilder();
        $sqlQuery = $sqlQueryBuilder
            ->select()
            ->from($tblDictionaryWordMeaning)
            ->where("", array(
                $colWordId => $urlWordParameter->url_word_id,
            ))
            ->orderBy($colMeaning, SqlOrderType::ASC)
            ->build();
        //DebugLog::log($sqlQuery);
        //$dbConn = $this->getDbConnection();
        $wordMeaningUrl = $searchModuleRequestModel->word_meaning_link_url;
        //
        $dbResult = $this->doRunDatabaseQuery($dbConn, $sqlQuery);
        if(empty($dbResult)) {
            return null;
        }
        $savedWordMeaningUrl = $wordMeaningUrl;
        $counter = 0;
        $retVal = "";
        foreach($dbResult as $row) {
            $counter++;
            $wordMeaningLanId = $row[$colLanId];
            $wordMeaningId = $row[$colMeaningId];
            $wordMeaning = $row[$colMeaning];
            //UrlWordMeaningParameter
            if(!empty($savedWordMeaningUrl)) {
                //
                $wordMeaningParameter = new UrlWordMeaningParameter();
                $wordMeaningParameter->url_word_language = $urlWordParameter->url_word_language;
                $wordMeaningParameter->url_word = $urlWordParameter->url_word;
                $wordMeaningParameter->search_word = $urlWordParameter->search_word;
                $wordMeaningParameter->url_word_meaning_language = $wordMeaningLanId;
                $wordMeaningParameter->url_meaning_id = $wordMeaningId;
                $wordMeaningParameter->url_meaning = $wordMeaning;
                //
                $wordMeaningUrl = "<a href=\""
                    . "{$savedWordMeaningUrl}?{$wordMeaningParameter->toQueryParameter()}\" target=\"_self\">"
                    . "{$wordMeaning}"
                    . "</a>\n";
                //$wordMeaningUrl = trim($wordMeaningUrl);
            } else {
                $wordMeaningUrl = $wordMeaning . "<br />\n";
            }
            //DebugLog::log($wordMeaningUrl);
            $retVal .= $wordMeaningUrl;
            //echo $wordMeaningUrl;
        }
        //$retVal = trim($retVal, "<br />");
        return $retVal;
    }

    private function getHtmlTableTop() {
        $retVal = "\n\n"
            . "<table class=\"table-dictionary-word-list\"  cellspacing=\"0\" cellpadding=\"0\">\n"
            . "<thead>\n"
            . "<tr style=\"cursor: default;\">\n"
            . "<th style=\"padding-left: 10px;\" width=\"1px\">Sn</th>\n"
            . "<th>Word</th>\n"
            . "<th>Pronunciation</th>\n"
            . "<th>Parts of Speech</th>\n"
            . "<th>Syllable</th>\n"
            . "<th style=\"padding-right: 10px;\">Word Meaning</th>\n"
            . "</tr>\n"
            . "</thead>\n"
            . "<tbody>\n"
            ."\n\n";
        return trim($retVal);
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