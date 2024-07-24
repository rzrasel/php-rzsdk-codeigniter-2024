<?php
namespace RzSDK\Service\Entry\Activity\Language;
?>
<?php
use RzSDK\Model\Entry\Language\DatabaseWordEntryModel;
use RzSDK\Model\HTTP\Request\Word\HttpWordEntryRequestModel;
use RzSDK\Service\Listener\ServiceListener;
use RzSDK\Model\HTTP\Request\Word\HttpWordEntryResponseModel;
use RzSDK\Database\Word\DictionaryWord;
use RzSDK\DatabaseSpace\DbWordTable;
use RzSDK\SqlQueryBuilder\SqlQueryBuilder;
use RzSDK\Database\SqliteConnection;
use RzSDK\Log\DebugLog;
?>
<?php
class WordEntryActivityService {
    //
    private ServiceListener $serviceListener;
    private HttpWordEntryResponseModel $wordEntryResponseModel;
    //
    public function __construct(ServiceListener $serviceListener) {
        $this->serviceListener = $serviceListener;
        $this->wordEntryResponseModel = new HttpWordEntryResponseModel();
        $this->wordEntryResponseModel->data = null;
        $this->wordEntryResponseModel->is_error = true;
        $this->wordEntryResponseModel->message = "User data is empty";
    }

    public function execute(HttpWordEntryRequestModel $wordEntryRequestModel) {
        //DebugLog::log($wordEntryRequestModel);
        $tempDictionaryWord = new DictionaryWord();
        $wordCol = $tempDictionaryWord->word;
        $tempDictionaryWord = null;
        //
        $wordEntryRequestModel->word = ucwords(strtolower($wordEntryRequestModel->word));
        //
        $dictionaryWordTableName = DbWordTable::dictionaryWordWithPrefix();
        $sqlQueryBuilder = new SqlQueryBuilder();
        $sqlQuery = $sqlQueryBuilder
            ->select()
            ->from($dictionaryWordTableName)
            ->where("", array(
                $wordCol => $wordEntryRequestModel->word,
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
            if($counter > 0 && !$wordEntryRequestModel->force_entry) {
                $this->wordEntryResponseModel->message = "Error! data already exists";
                $this->wordEntryResponseModel->is_error = true;
                $this->serviceListener->onError($this->wordEntryResponseModel, $this->wordEntryResponseModel->message);
                return;
            }
        }
        $this->dbDataEntry($wordEntryRequestModel);
    }

    private function dbDataEntry(HttpWordEntryRequestModel $wordEntryRequestModel) {
        //DebugLog::log($wordEntryRequestModel);
        $dbWordEntryModel = new DatabaseWordEntryModel();
        $insertDataSet = $dbWordEntryModel->getWordInsertDataSet($wordEntryRequestModel);
        $insertDataSet = $insertDataSet->getColumnWithKey();
        //DebugLog::log($insertDataSet);
        $dictionaryWordTableName = DbWordTable::dictionaryWordWithPrefix();
        $sqlQueryBuilder = new SqlQueryBuilder();
        $sqlQuery = $sqlQueryBuilder->insert($dictionaryWordTableName)
            ->values($insertDataSet)
            ->build();
        //DebugLog::log($sqlQuery);
        $dbConn = $this->getDbConnection();
        $dbResult = $this->doRunDatabaseQuery($dbConn, $sqlQuery);
        $dbResult = null;
        $this->wordEntryResponseModel->data = $wordEntryRequestModel;
        $this->wordEntryResponseModel->message = "Successfully entry completed.";
        $this->wordEntryResponseModel->is_error = false;
        $this->serviceListener->onSuccess($this->wordEntryResponseModel, $this->wordEntryResponseModel->message);
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