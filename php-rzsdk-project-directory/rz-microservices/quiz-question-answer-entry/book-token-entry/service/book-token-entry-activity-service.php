<?php
namespace RzSDK\Quiz\Service\Book\Token\Entry;
?>
<?php
use RzSDK\Service\Listener\ServiceListener;
use RzSDK\Quiz\Model\HTTP\Request\Book\Token\Parameter\RequestBookTokenEntryQueryModel;
use RzSDK\Database\SqliteConnection;
use RzSDK\Database\Quiz\TblBookIndex;
use RzSDK\SqlQueryBuilder\SqlQueryBuilder;
use RzSDK\Database\Space\DbQuizTable;
use RzSDK\Quiz\Model\Database\Book\Token\Entry\DbBookTokenEntryModel;
use RzSDK\Utils\String\StringHelper;
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
        //DebugLog::log($this->bookTokenEntryQueryModel);
        $this->isBookTokenExists();
    }

    private function isBookTokenExists() {
        //
        $tempTblBookIndex = new TblBookIndex();
        $colBookTokenName = $tempTblBookIndex->book_token_name;
        $tempTblBookIndex = null;
        //
        $bookTokenName = $this->bookTokenEntryQueryModel->book_token_name;
        $bookTokenName = StringHelper::toSingleSpace($bookTokenName);
        $bookTokenName = StringHelper::toUCWords($bookTokenName);
        //DebugLog::log($bookTokenName);
        //return;
        //
        $bookTokenTableName = DbQuizTable::bookTokenWithPrefix();
        //
        $sqlQueryBuilder = new SqlQueryBuilder();
        $sqlQuery = $sqlQueryBuilder
            ->select()
            ->from($bookTokenTableName)
            ->where($bookTokenTableName, array(
                $colBookTokenName => $bookTokenName,
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
            $this->onError(null, "Error! Language \"{$this->bookTokenEntryQueryModel->book_token_name}\" already exist");
            return;
        }
        //
        $this->runBookTokenDbInsert();
    }

    private function runBookTokenDbInsert() {
        //
        $dbBookTokenEntryModel = new DbBookTokenEntryModel();
        $insertDataSet = $dbBookTokenEntryModel->getBookTokenInsertDataSet($this->bookTokenEntryQueryModel);
        $insertDataSet = $insertDataSet->getColumnWithKey();
        //DebugLog::log($insertDataSet);
        //
        $bookTokenTableName = DbQuizTable::bookTokenWithPrefix();
        $sqlQueryBuilder = new SqlQueryBuilder();
        $sqlQuery = $sqlQueryBuilder->insert($bookTokenTableName)
            ->values($insertDataSet)
            ->build();
        //DebugLog::log($sqlQuery);
        $dbConn = $this->getDbConnection();
        $dbResult = $this->doRunDatabaseQuery($dbConn, $sqlQuery);
        $dbResult = null;
        $this->onSuccess($this->bookTokenEntryQueryModel, "Successfully inserted \"{$this->bookTokenEntryQueryModel->book_token_name}\"");
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
