<?php
namespace RzSDK\Quiz\Service\Character\Map\Entry;
?>
<?php
use RzSDK\Service\Listener\ServiceListener;
use RzSDK\Quiz\Model\HTTP\Request\Character\Map\Parameter\RequestCharacterMapEntryQueryModel;
use RzSDK\Database\Space\DbQuizTable;
use RzSDK\SqlQueryBuilder\SqlQueryBuilder;
use RzSDK\Database\Quiz\TblCharacterMapping;
use RzSDK\Database\SqliteConnection;
use RzSDK\Universal\Character\Token\BuildCharacterTokenSelectOptions;
use RzSDK\Quiz\Model\Database\Character\Map\Entry\DbCharacterTableMapEntryModel;
use RzSDK\Log\DebugLog;
?>
<?php
class CharacterTableMapEntryActivityService implements ServiceListener {
    //
    public ServiceListener $serviceListener;
    public RequestCharacterMapEntryQueryModel $characterMapEntryQueryModel;
    //

    public function __construct(ServiceListener $serviceListener) {
        $this->serviceListener = $serviceListener;
    }

    public function execute(RequestCharacterMapEntryQueryModel $characterMapEntryQueryModel) {
        $this->characterMapEntryQueryModel = $characterMapEntryQueryModel;
        //DebugLog::log($this->characterMapEntryQueryModel);
        $this->runExecute();
    }

    private function runExecute() {
        //
        $colCharIndexId = $this->characterMapEntryQueryModel->character_token_id;
        $colAsciiChar = $this->characterMapEntryQueryModel->character_name;
        $dataSet = BuildCharacterTokenSelectOptions::getDataSet($colCharIndexId);
        $colCharIndexId = $dataSet[0];
        $colStrChar = $dataSet[1];
        //
        $isExists = $this->isCharacterExists($colCharIndexId, $colStrChar, $colAsciiChar);
        if($isExists) {
            $this->onError(null, "Error! Character \"{$colAsciiChar}\" already exist");
            return;
        }
        $this->runCharacterInsert($colCharIndexId, $colStrChar, $colAsciiChar);
        $this->onSuccess(null, "Successfully \"{$colAsciiChar}\" inserted");
    }

    private function runCharacterInsert($tokenId, $tokenChar, $asciiChar) {
        $characterTableTokenEntryModel = new DbCharacterTableMapEntryModel();
        $insertDataSet = $characterTableTokenEntryModel->getCharacterMapInsertDataSet($tokenId, $tokenChar, $asciiChar);
        $insertDataSet = $insertDataSet->getColumnWithKey();
        //DebugLog::log($insertDataSet);
        $characterMappingTableName = DbQuizTable::characterMappingWithPrefix();
        //
        $sqlQueryBuilder = new SqlQueryBuilder();
        $sqlQuery = $sqlQueryBuilder->insert($characterMappingTableName)
            ->values($insertDataSet)
            ->build();
        //DebugLog::log($sqlQuery);
        //
        $dbConn = $this->getDbConnection();
        $dbResult = $this->doRunDatabaseQuery($dbConn, $sqlQuery);
        $dbResult = null;
    }

    private function isCharacterExists($tokenId, $tokenChar, $asciiChar) {
        //
        $temTblCharTableIndex = new TblCharacterMapping();
        $colCharIndexId = $temTblCharTableIndex->char_index_id;
        $colStrChar = $temTblCharTableIndex->str_char;
        $colAsciiChar = $temTblCharTableIndex->ascii_char;
        $temTblCharTableIndex = null;
        //
        $characterMappingTableName = DbQuizTable::characterMappingWithPrefix();
        //
        $sqlQueryBuilder = new SqlQueryBuilder();
        $sqlQuery = $sqlQueryBuilder
            ->select()
            ->from($characterMappingTableName)
            ->where($characterMappingTableName, array(
                $colCharIndexId => $tokenId,
                $colStrChar => $tokenChar,
                $colAsciiChar => $asciiChar,
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

    public function onSuccess($dataSet, $message) {
        /*DebugLog::log($dataSet);
        DebugLog::log($message);*/
        $this->serviceListener->onSuccess($dataSet, $message);
    }
}
?>
