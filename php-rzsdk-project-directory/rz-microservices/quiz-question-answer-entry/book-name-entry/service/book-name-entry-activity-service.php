<?php
namespace RzSDK\Quiz\Service\Book\Token\Entry;
?>
    <?php
use RzSDK\Service\Listener\ServiceListener;
use RzSDK\Database\SqliteConnection;
use RzSDK\SqlQueryBuilder\SqlQueryBuilder;
use RzSDK\Database\Space\DbQuizTable;
use RzSDK\Quiz\Model\HTTP\Request\Book\Name\Parameter\RequestBookNameEntryQueryModel;
use RzSDK\Database\Quiz\TblBookName;
use RzSDK\Quiz\Model\Database\Book\Name\Entry\DbBookNameEntryModel;
use RzSDK\Utils\String\StringHelper;
use RzSDK\Log\DebugLog;
?>
<?php
class BookNameEntryActivityService implements ServiceListener {
    //
    public ServiceListener $serviceListener;
    public RequestBookNameEntryQueryModel $bookNameEntryQueryModel;
    //

    public function __construct(ServiceListener $serviceListener) {
        $this->serviceListener = $serviceListener;
    }

    public function execute(RequestBookNameEntryQueryModel $bookNameEntryQueryModel) {
        $this->bookNameEntryQueryModel = $bookNameEntryQueryModel;
        //DebugLog::log($this->bookNameEntryQueryModel);
        $this->runExecute();
    }

    private function runExecute() {
        //DebugLog::log($this->bookNameEntryQueryModel);
        /*$defaultValue = $this->getDefaultBookName($lanId, $bookTokenId);
        DebugLog::log($defaultValue);*/
        $this->isBookNameExists();
    }

    private function isBookNameExists() {
        //
        $tempTblBookName = new TblBookName();
        $colLanId = $tempTblBookName->lan_id;
        $colBookTokenId = $tempTblBookName->book_token_id;
        $colBookNameId = $tempTblBookName->book_name_id;
        $colBookName = $tempTblBookName->book_name;
        $colBookSlug = $tempTblBookName->slug;
        $colIsDefault = $tempTblBookName->is_default;
        $tempTblBookName = null;
        //
        $lanId = $this->bookNameEntryQueryModel->language_id;
        $bookTokenId = $this->bookNameEntryQueryModel->book_token_id;
        $bookName = $this->bookNameEntryQueryModel->book_name;
        $bookName = StringHelper::toSingleSpace($bookName);
        $bookSlug = $this->bookNameEntryQueryModel->book_name_slug;
        $bookSlug = StringHelper::toSlugify($bookSlug);
        //
        $bookNameTableName = DbQuizTable::bookNameWithPrefix();
        //
        /*$sqlQueryBuilder = new SqlQueryBuilder();
        $sqlQuery = $sqlQueryBuilder
            ->select()
            ->from($bookNameTableName)
            ->where($bookNameTableName, array(
                $colLanId => $lanId,
                $colBookTokenId => $bookTokenId,
                $colBookName => $this->bookNameEntryQueryModel->book_name,
            ))
            ->where($bookNameTableName, array(
                $colBookSlug => $this->bookNameEntryQueryModel->book_name_slug,
            ), false)
            ->build();*/
        /*$sqlQuery = "SELECT * FROM {$bookNameTableName} "
            . " WHERE ("
            . "{$colLanId} = '{$lanId}'"
            . " AND {$colBookTokenId} = '{$bookTokenId}'"
            . " AND {$colBookName} = '{$bookName}'"
            . ")"
            . " OR {$colBookSlug} = '{$bookSlug}'"
            . ";";*/
        $sqlQuery = "SELECT * FROM {$bookNameTableName} "
            . " WHERE"
            . " ("
            . " {$colLanId} = '{$lanId}'"
            . " AND {$colBookTokenId} = '{$bookTokenId}'"
            . " AND {$colBookName} = '{$bookName}'"
            . ")"
            . " OR"
            . " ("
            . "{$colLanId} = '{$lanId}'"
            . " AND {$colBookTokenId} = '{$bookTokenId}'"
            . " AND {$colBookSlug} = '{$bookSlug}'"
            . ")"
            . ";";
        $sqlQuery = StringHelper::toSingleSpace($sqlQuery);
        //DebugLog::log($sqlQuery);
        //
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
        //
        if($counter > 0) {
            $this->onError(null, "Error! Book name \"{$this->bookNameEntryQueryModel->book_name}\" or slug \"{$this->bookNameEntryQueryModel->book_name_slug}\" already exist");
            return;
        }
        //
        $this->runBookNameDbInsert();
        //
    }

    private function runBookNameDbInsert() {
        //
        $lanId = $this->bookNameEntryQueryModel->language_id;
        $bookTokenId = $this->bookNameEntryQueryModel->book_token_id;
        //
        $isDefault = false;
        if($this->bookNameEntryQueryModel->book_name_is_default == 1) {
            $isDefault = true;
        }
        $defaultBookName = $this->getDefaultBookName($lanId, $bookTokenId);
        //DebugLog::log($defaultBookName);
        if(empty($defaultBookName)) {
            $isDefault = true;
        }
        // If isDefault is true and have default, update database and change default value
        if($isDefault && !empty($defaultBookName)) {
            $this->onUpdateDefaultBookName($lanId, $bookTokenId, $defaultBookName);
        }
        $dbBookNameEntryModel = new DbBookNameEntryModel();
        $insertDataSet = $dbBookNameEntryModel->getBookNameInsertDataSet($this->bookNameEntryQueryModel, $isDefault);
        $insertDataSet = $insertDataSet->getColumnWithKey();
        //DebugLog::log($insertDataSet);
        $bookNameTableName = DbQuizTable::bookNameWithPrefix();
        $sqlQueryBuilder = new SqlQueryBuilder();
        $sqlQuery = $sqlQueryBuilder->insert($bookNameTableName)
            ->values($insertDataSet)
            ->build();
        //DebugLog::log($sqlQuery);
        $dbConn = $this->getDbConnection();
        $dbResult = $this->doRunDatabaseQuery($dbConn, $sqlQuery);
        $dbResult = null;
        //
        $this->onSuccess($this->bookNameEntryQueryModel, "Successfully inserted \"{$this->bookNameEntryQueryModel->book_name}\"");
    }

    private function onUpdateDefaultBookName($lanId, $bookTokenId, $defaultBookName) {
        //
        $tempTblBookName = new TblBookName();
        $colLanId = $tempTblBookName->lan_id;
        $colBookTokenId = $tempTblBookName->book_token_id;
        $colBookNameId = $tempTblBookName->book_name_id;
        $colBookName = $tempTblBookName->book_name;
        $colIsDefault = $tempTblBookName->is_default;
        $tempTblBookName = null;
        //
        $updateDataSet = array(
            $colIsDefault => false,
        );
        //
        $wordWhereDataSet = array(
            $colLanId => $lanId,
            $colBookTokenId => $bookTokenId,
        );
        //
        $bookNameTableName = DbQuizTable::bookNameWithPrefix();
        $sqlQueryBuilder = new SqlQueryBuilder();
        $sqlQuery = $sqlQueryBuilder
            ->update($bookNameTableName)
            ->set($updateDataSet)
            ->where("", $wordWhereDataSet)
            ->build();
        //DebugLog::log($sqlQuery);
        $dbConn = $this->getDbConnection();
        $dbResult = $this->doRunDatabaseQuery($dbConn, $sqlQuery);
        $dbResult = null;
        //$this->onSuccess($this->bookNameEntryQueryModel, "ggSuccessfully inserted \"{$this->bookNameEntryQueryModel->book_name}\"");
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

    private function getDefaultBookName($lanId, $bookTokenId) {
        //
        $tempTblBookName = new TblBookName();
        $colLanId = $tempTblBookName->lan_id;
        $colBookTokenId = $tempTblBookName->book_token_id;
        $colBookNameId = $tempTblBookName->book_name_id;
        $colBookName = $tempTblBookName->book_name;
        $colIsDefault = $tempTblBookName->is_default;
        $tempTblBookName = null;
        //
        $bookNameTableName = DbQuizTable::bookNameWithPrefix();
        //
        $sqlQueryBuilder = new SqlQueryBuilder();
        $sqlQuery = $sqlQueryBuilder
            ->select()
            ->from($bookNameTableName)
            ->where($bookNameTableName, array(
                $colLanId => $lanId,
                $colBookTokenId => $bookTokenId,
                $colIsDefault => true,
            ))
            ->build();
        //DebugLog::log($sqlQuery);
        $dbConn = $this->getDbConnection();
        $dbResult = $this->doRunDatabaseQuery($dbConn, $sqlQuery);
        if(empty($dbResult)) {
            return array();
        }
        //
        $retVal = array();
        $counter = 0;
        foreach($dbResult as $row) {
            //DebugLog::log($row);
            $retVal["id"] = $row[$colBookNameId];
            $counter++;
        }
        if($counter < 1) {
            return array();
        }
        return $retVal;
    }
}
?>