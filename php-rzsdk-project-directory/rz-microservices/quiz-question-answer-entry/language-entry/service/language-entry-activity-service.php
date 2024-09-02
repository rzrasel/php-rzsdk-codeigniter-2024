<?php
namespace RzSDK\Quiz\Service\Language\Entry;
?>
<?php

use Error;
use RzSDK\Service\Listener\ServiceListener;
use RzSDK\Quiz\Model\HTTP\Request\Language\Parameter\RequestLanguageEntryQueryModel;
use RzSDK\Database\SqliteConnection;
use RzSDK\SqlQueryBuilder\SqlQueryBuilder;
use RzSDK\SqlQueryBuilder\SqlOrderType;
use RzSDK\Database\Space\DbQuizTable;
use RzSDK\Database\Quiz\TblLanguage;
use RzSDK\Quiz\Model\Database\Language\Entry\DbLanguageEntryModel;
use RzSDK\Log\DebugLog;
?>
<?php
class LanguageEntryActivityService implements ServiceListener {
    //
    public ServiceListener $serviceListener;
    public RequestLanguageEntryQueryModel $languageEntryQueryModel;
    //

    public function __construct(ServiceListener $serviceListener) {
        $this->serviceListener = $serviceListener;
    }

    public function execute(RequestLanguageEntryQueryModel $languageEntryQueryModel) {
        $this->languageEntryQueryModel = $languageEntryQueryModel;
        //DebugLog::log($this->languageEntryQueryModel);
        $this->runExecute();
    }

    private function runExecute() {
        //DebugLog::log($this->languageEntryQueryModel);
        $this->isLanguageExists();
    }

    private function isLanguageExists() {
        //
        $tempTblLanguage = new TblLanguage();
        $colLanName = $tempTblLanguage->lan_name;
        $tempTblLanguage = null;
        //
        $languageTableName = DbQuizTable::languageWithPrefix();
        //
        $sqlQueryBuilder = new SqlQueryBuilder();
        $sqlQuery = $sqlQueryBuilder
            ->select()
            ->from($languageTableName)
            ->where($languageTableName, array(
                $colLanName => $this->languageEntryQueryModel->language_name,
            ))
            ->build();
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
        if($counter > 0) {
            $this->onError(null, "Error! Language \"{$this->languageEntryQueryModel->language_name}\" already exist");
            return;
        }
        //
        $this->runLanguageDbInsert();
    }

    private function runLanguageDbInsert() {
        //
        $dbLanguageEntryModel = new DbLanguageEntryModel();
        $insertDataSet = $dbLanguageEntryModel->getLanguageInsertDataSet($this->languageEntryQueryModel);
        $insertDataSet = $insertDataSet->getColumnWithKey();
        //
        $dictionaryWordTableName = DbQuizTable::languageWithPrefix();
        $sqlQueryBuilder = new SqlQueryBuilder();
        $sqlQuery = $sqlQueryBuilder->insert($dictionaryWordTableName)
            ->values($insertDataSet)
            ->build();
        //DebugLog::log($sqlQuery);
        $dbConn = $this->getDbConnection();
        $dbResult = $this->doRunDatabaseQuery($dbConn, $sqlQuery);
        $dbResult = null;
        $this->onSuccess($this->languageEntryQueryModel, "Successfully inserted \"{$this->languageEntryQueryModel->language_name}\"");
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

    function onSuccess($dataSet, $message) {
        /*DebugLog::log($dataSet);
        DebugLog::log($message);*/
        $this->serviceListener->onSuccess($dataSet, $message);
    }
}
?>
