<?php
namespace RzSDK\Quiz\Service\Book\Token\Entry;
?>
<?php
use RzSDK\Service\Listener\ServiceListener;
use RzSDK\Quiz\Model\HTTP\Request\Book\Token\Parameter\RequestBookTokenEntryQueryModel;
use RzSDK\Database\SqliteConnection;
use RzSDK\Log\DebugLog;
?>
<?php
class BookTokenEntryActivityService implements ServiceListener {
    //
    public ServiceListener $serviceListener;
    public RequestBookTokenEntryQueryModel $bookTokenEntryQueryModel;
    //

    public function __construct(ServiceListener $serviceListener) {
        $this->serviceListener = $serviceListener;
    }

    public function execute(RequestBookTokenEntryQueryModel $bookTokenEntryQueryModel) {
        $this->bookTokenEntryQueryModel = $bookTokenEntryQueryModel;
        //DebugLog::log($this->bookTokenEntryQueryModel);
        $this->runExecute();
    }

    private function runExecute() {
        DebugLog::log($this->bookTokenEntryQueryModel);
        //$this->isLanguageExists();
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
