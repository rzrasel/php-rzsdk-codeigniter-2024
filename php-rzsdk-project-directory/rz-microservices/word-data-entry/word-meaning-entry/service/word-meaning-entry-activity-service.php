<?php
namespace RzSDK\Service\Entry\Meaning\Activity;
?>
<?php
use RzSDK\Service\Listener\ServiceListener;
use RzSDK\Model\HTTP\Request\Word\Meaning\Entry\Model\HttpWordMeaningEntryResponseModel;
use RzSDK\Model\HTTP\Request\Word\Meaning\Entry\Model\HttpWordMeaningEntryRequestModel;
use RzSDK\Database\Word\Meaning\DictionaryWordMeaning;
use RzSDK\DatabaseSpace\DbWordTable;
use RzSDK\SqlQueryBuilder\SqlQueryBuilder;
use RzSDK\Model\Entry\Meaning\DatabaseWordMeaningEntryModel;
use RzSDK\Database\SqliteConnection;
use RzSDK\Log\DebugLog;
?>
<?php
class WordMeaningEntryActivityService {
    //
    public ServiceListener $serviceListener;
    private HttpWordMeaningEntryResponseModel $meaningEntryResponseModel;
    //

    public function __construct(ServiceListener $serviceListener) {
        $this->serviceListener = $serviceListener;
        $this->meaningEntryResponseModel = new HttpWordMeaningEntryResponseModel();
        $this->meaningEntryResponseModel->data = null;
        $this->meaningEntryResponseModel->is_error = true;
        $this->meaningEntryResponseModel->message = "Unknown error";
    }

    public function execute(HttpWordMeaningEntryRequestModel $meaningEntryRequestModel) {
        //DebugLog::log($meaningEntryRequestModel);
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
                $colMeaning => $meaningEntryRequestModel->meaning,
                $colWordId => $meaningEntryRequestModel->url_word_id,
            ))
            ->build();
        //DebugLog::log($sqlQuery);
        $dbConn = $this->getDbConnection();
        $dbResult = $this->doRunDatabaseQuery($dbConn, $sqlQuery);
        if(!empty($dbResult)) {
            $counter = 0;
            foreach($dbResult as $row) {
                $counter++;
            }
            if($counter > 0) {
                $this->meaningEntryResponseModel->message = "Error! data already exists";
                $this->meaningEntryResponseModel->is_error = true;
                $this->serviceListener->onError($this->meaningEntryResponseModel, $this->meaningEntryResponseModel->message);
                return;
            }
        }
        $this->dbDataEntry($meaningEntryRequestModel);
    }

    private function dbDataEntry(HttpWordMeaningEntryRequestModel $meaningEntryRequestModel) {
        //DebugLog::log($meaningEntryRequestModel);
        $dbWordMeaningEntryModel = new DatabaseWordMeaningEntryModel();
        $insertDataSet = $dbWordMeaningEntryModel->getWordMeaningInsertDataSet($meaningEntryRequestModel);
        $insertDataSet = $insertDataSet->getColumnWithKey();
        //DebugLog::log($insertDataSet);
        $tblDictionaryWordMeaning = DbWordTable::dictionaryWordMeaningWithPrefix();
        $sqlQueryBuilder = new SqlQueryBuilder();
        $sqlQuery = $sqlQueryBuilder->insert($tblDictionaryWordMeaning)
            ->values($insertDataSet)
            ->build();
        //DebugLog::log($sqlQuery);
        $dbConn = $this->getDbConnection();
        $dbResult = $this->doRunDatabaseQuery($dbConn, $sqlQuery);
        $dbResult = null;
        $this->meaningEntryResponseModel->data = $meaningEntryRequestModel;
        $this->meaningEntryResponseModel->message = "Successfully entry completed.";
        $this->meaningEntryResponseModel->is_error = false;
        $this->serviceListener->onSuccess($this->meaningEntryResponseModel, $this->meaningEntryResponseModel->message);
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