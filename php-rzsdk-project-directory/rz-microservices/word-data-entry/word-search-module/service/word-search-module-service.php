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
        $lanIdCol = $tempDictionaryWord->lan_id;
        $wordIdCol = $tempDictionaryWord->word_id;
        $wordCol = $tempDictionaryWord->word;
        $wordCreateDate = $tempDictionaryWord->created_date;
        $tempDictionaryWord = null;
        //
        $tempDictionaryWordMeaning = new DictionaryWordMeaning();
        $meaningWordIdCol = $tempDictionaryWordMeaning->word_id;
        $tempDictionaryWordMeaning = null;
        //
        $dictionaryWordTableName = DbWordTable::dictionaryWordWithPrefix();
        $dictionaryWordMeaningTableName = DbWordTable::dictionaryWordMeaningWithPrefix();
        $sqlQueryBuilder = new SqlQueryBuilder();
        $sqlQuery = $sqlQueryBuilder
            ->select()
            ->from($dictionaryWordTableName)
            //->innerJoin($dictionaryWordTableName, $dictionaryWordMeaningTableName, $wordIdCol, $meaningWordIdCol)
            //->leftJoin($dictionaryWordTableName, $dictionaryWordMeaningTableName, $wordIdCol, $meaningWordIdCol)
            /*->where($dictionaryWordTableName, array(
                //$wordCol => $searchModuleRequestModel->word,
                "{$wordCol} LIKE '{$searchModuleRequestModel->search_word}%'",
            ))*/
            ->where($dictionaryWordTableName, array(
                //$wordCol => $searchModuleRequestModel->word,
                $wordCol => "{LIKE}{$searchModuleRequestModel->search_word}%",
                $lanIdCol => $searchModuleRequestModel->word_language,
            ))
            /*->where($dictionaryWordTableName, array(
                $lanIdCol => $searchModuleRequestModel->word_language,
            ))*/
            ->orderByWithTable($dictionaryWordTableName, $wordCol, SqlOrderType::ASC)
            ->orderByWithTable($dictionaryWordTableName, $wordCreateDate, SqlOrderType::DESC)
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
            $dataWordId = $row["word_id"];
            $wordMeaningDataSet = $this->getWordMeaningDatabaseDataSet($dbConn, $dataWordId);
            //
            $urlSelf = " "
                . "{$searchModuleRequestModel->word_link_url}?"
                . "word_language={$searchModuleRequestModel->word_language}"
                . "&search_word={$searchModuleRequestModel->search_word}"
                . "&url_word_id={$dataWordId}"
                . "&url_word={$row["word"]}"
                . " ";
            //url_word_id
            //$urlSelf = urlencode($urlSelf);
            $retVal .= "<tr>\n"
                . "<td style=\"padding-left: 10px;\">{$counter}</td>\n"
                . "<td><a href=\"{$urlSelf}\" target=\"_self\" >{$row["word"]}</a></td>\n"
                //. "<td><a href=\"{$searchModuleRequestModel->word_link_url}?word_language={$searchModuleRequestModel->language}&url_word={$row["word"]}&url_word_id={$row["word_id"]}\" target=\"_blank\" >{$row["word"]}</a></td>\n"
                . "<td>{$row["pronunciation"]}</td>\n"
                . "<td>{$row["parts_of_speech"]}</td>\n"
                . "<td>{$row["syllable"]}</td>\n"
                . "<td style=\"padding-right: 10px;\">{$wordMeaningDataSet}</td>\n"
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

    private function getWordMeaningDatabaseDataSet($dbConn, $wordId) {
        //
        $tempDictionaryWordMeaning = new DictionaryWordMeaning();
        $colLanId = $tempDictionaryWordMeaning->lan_id;
        $colWordId = $tempDictionaryWordMeaning->word_id;
        $colMeaning = $tempDictionaryWordMeaning->meaning;
        $tempDictionaryWordMeaning = null;
        //
        $tblDictionaryWordMeaning = DbWordTable::dictionaryWordMeaningWithPrefix();
        $sqlQueryBuilder = new SqlQueryBuilder();
        $sqlQuery = $sqlQueryBuilder
            ->select()
            ->from($tblDictionaryWordMeaning)
            ->where("", array(
                $colWordId => $wordId,
            ))
            ->orderBy($colMeaning, SqlOrderType::ASC)
            ->build();
        //DebugLog::log($sqlQuery);
        //$dbConn = $this->getDbConnection();
        $dbResult = $this->doRunDatabaseQuery($dbConn, $sqlQuery);
        if(empty($dbResult)) {
            return null;
        }
        $counter = 0;
        $retVal = "";
        foreach($dbResult as $row) {
            $counter++;
            $retVal .= $row[$colMeaning] . "<br />";
        }
        $retVal = trim($retVal, "<br />");
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