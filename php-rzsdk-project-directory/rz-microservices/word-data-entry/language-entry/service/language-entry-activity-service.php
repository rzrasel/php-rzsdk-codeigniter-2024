<?php
namespace RzSDK\Service\Entry\Activity\Language;
?>
<?php
use RzSDK\Service\Listener\ServiceListener;
use RzSDK\Model\HTTP\Request\Language\HttpLanguageEntryRequestModel;
use RzSDK\DatabaseSpace\DbWordTable;
use RzSDK\Database\Word\TblLanguage;
use RzSDK\DateTime\DateTime;
use RzSDK\Model\Entry\Language\DatabaseLanguageEntryModel;
use RzSDK\SqlQueryBuilder\SqlQueryBuilder;
use RzSDK\Model\HTTP\Request\Language\HttpLanguageEntryResponseModel;
use RzSDK\Database\SqliteConnection;
use RzSDK\Log\DebugLog;
?>
<?php
class LanguageEntryActivityService {
    //
    private ServiceListener $serviceListener;
    private HttpLanguageEntryResponseModel $languageEntryResponseModel;
    //
    public function __construct(ServiceListener $serviceListener) {
        $this->serviceListener = $serviceListener;
        $this->languageEntryResponseModel = new HttpLanguageEntryResponseModel();
        $this->languageEntryResponseModel->data = null;
        $this->languageEntryResponseModel->is_error = true;
        $this->languageEntryResponseModel->message = "User data is empty";
    }

    public function execute(HttpLanguageEntryRequestModel $languageEntryRequestModel) {
        //DebugLog::log($languageEntryRequestModel);
        $tempTblLanguage = new TblLanguage();
        $langNameCol = $tempTblLanguage->lan_name;
        $tempTblLanguage = null;
        //DebugLog::log($status);
        //
        $languageEntryRequestModel->language = ucwords(strtolower($languageEntryRequestModel->language));
        //
        $languageTableName = DbWordTable::languageWithPrefix();
        $sqlQueryBuilder = new SqlQueryBuilder();
        $sqlQuery = $sqlQueryBuilder
            ->select()
            ->from($languageTableName)
            ->where("", array(
                $langNameCol => $languageEntryRequestModel->language,
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
                $this->languageEntryResponseModel->message = "Error! data already exists";
                $this->languageEntryResponseModel->is_error = true;
                $this->serviceListener->onError($this->languageEntryResponseModel, $this->languageEntryResponseModel->message);
                return;
            }
        }
        $this->dbDataEntry($languageEntryRequestModel);
    }

    private function dbDataEntry(HttpLanguageEntryRequestModel $languageEntryRequestModel) {
        //DebugLog::log($languageEntryRequestModel);
        $dbLanguageEntryModel = new DatabaseLanguageEntryModel();
        $insertDataSet = $dbLanguageEntryModel->getLanguageInsertDataSet($languageEntryRequestModel);
        $insertDataSet = $insertDataSet->getColumnWithKey();
        //DebugLog::log($insertDataSet);
        $languageTableName = DbWordTable::languageWithPrefix();
        $sqlQueryBuilder = new SqlQueryBuilder();
        $sqlQuery = $sqlQueryBuilder->insert($languageTableName)
            ->values($insertDataSet)
            ->build();
        //DebugLog::log($sqlQuery);
        $dbConn = $this->getDbConnection();
        $dbResult = $this->doRunDatabaseQuery($dbConn, $sqlQuery);
        $dbResult = null;
        $this->languageEntryResponseModel->data = $languageEntryRequestModel;
        $this->languageEntryResponseModel->message = "Successfully entry completed.";
        $this->languageEntryResponseModel->is_error = false;
        $this->serviceListener->onSuccess($this->languageEntryResponseModel, $this->languageEntryResponseModel->message);
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
