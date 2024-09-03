<?php
namespace RzSDK\Quiz\Service\Character\Token\Entry;
?>
<?php
use RzSDK\Service\Listener\ServiceListener;
use RzSDK\Database\SqliteConnection;
use RzSDK\Database\Quiz\TblCharacterTableIndex;
use RzSDK\SqlQueryBuilder\SqlQueryBuilder;
use RzSDK\Database\Space\DbQuizTable;
use RzSDK\Utils\String\StringHelper;
use RzSDK\Quiz\Model\Database\Character\Index\Entry\DbCharacterTableTokenEntryModel;
use RzSDK\Log\DebugLog;
?>
<?php
class CharacterTableTokenEntryActivityService implements ServiceListener {
    //
    public ServiceListener $serviceListener;
    //public RequestBookNameEntryQueryModel $bookNameEntryQueryModel;
    //

    public function __construct(ServiceListener $serviceListener) {
        $this->serviceListener = $serviceListener;
    }

    public function execute() {
        //$this->onSuccess(null, "Successfully inserted");
        $this->runExecute();
    }

    private function runExecute() {
        $dataSet = "001234456789abcdefghijklmnopqrstuvwxyz";
        //$dataSet = "1234456789abcdefghijklmnopqrstuvwxyz";
        foreach(mb_str_split($dataSet) as $char) {
            $isExists = $this->isCharacterExists($char);
            if(!$isExists) {
                $this->runCharacterInsert($char);
                $this->onSuccess(null, "Successfully \"{$char}\" inserted");
            }
            //$this->onSuccess(null, "Successfully \"{$char}\" inserted");
            //sleep(1);
            usleep(3000);
        }
        $this->onSuccess(null, "Successfully inserted");
    }

    private function runCharacterInsert($char) {
        $characterTableTokenEntryModel = new DbCharacterTableTokenEntryModel();
        $insertDataSet = $characterTableTokenEntryModel->getBookNameInsertDataSet($char);
        $insertDataSet = $insertDataSet->getColumnWithKey();
        //DebugLog::log($insertDataSet);
        $characterTableTokenTableName = DbQuizTable::characterTableTokenWithPrefix();
        //
        $sqlQueryBuilder = new SqlQueryBuilder();
        $sqlQuery = $sqlQueryBuilder->insert($characterTableTokenTableName)
            ->values($insertDataSet)
            ->build();
        //DebugLog::log($sqlQuery);
        //
        $dbConn = $this->getDbConnection();
        $dbResult = $this->doRunDatabaseQuery($dbConn, $sqlQuery);
        $dbResult = null;
    }

    private function isCharacterExists($char) {
        //
        $temTblCharTableIndex = new TblCharacterTableIndex();
        $colStrChar = $temTblCharTableIndex->str_char;
        $tempTblBookName = null;
        //
        $characterTableTokenTableName = DbQuizTable::characterTableTokenWithPrefix();
        //
        $sqlQueryBuilder = new SqlQueryBuilder();
        $sqlQuery = $sqlQueryBuilder
            ->select()
            ->from($characterTableTokenTableName)
            ->where($characterTableTokenTableName, array(
                $colStrChar => $char,
            ))
            ->build();
        //DebugLog::log($sqlQuery);
        //
        $dbConn = $this->getDbConnection();
        $dbResult = $this->doRunDatabaseQuery($dbConn, $sqlQuery);
        if(empty($dbResult)) {
            $dbResult = null;
            return false;
        }
        //
        $counter = 0;
        foreach($dbResult as $row) {
            //DebugLog::log($row);
            $counter++;
        }
        $dbResult = null;
        if($counter > 0) {
            return true;
        }
        return false;
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
