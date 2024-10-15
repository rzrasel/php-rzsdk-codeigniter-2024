<?php
namespace RzSDK\Universal\Character\Token;
?>
<?php
use RzSDK\Database\SqliteConnection;
use RzSDK\Database\Space\DbQuizTable;
use RzSDK\Database\Quiz\TblCharacterTableIndex;
use RzSDK\SqlQueryBuilder\SqlQueryBuilder;
use RzSDK\Log\DebugLog;
?>
<?php
class PullCharacterTokenList {
    //
    public const TOKEN_ID    = "id";
    public const TOKEN_NAME  = "name";
    public const TOKEN_SLUG  = "slug";
    //
    public function __construct() {
    }

    public function getCharacterToken() {
        $sqlQuery = $this->getSelectSql();
        //DebugLog::log($sqlQuery);
        $dbConn = $this->getDbConnection();
        $dbResult = $this->doRunDatabaseQuery($dbConn, $sqlQuery);
        if(empty($dbResult)) {
            return array();
        }
        //
        $tempTblCharacterTableIndex = new TblCharacterTableIndex();
        $colCharIndexId = $tempTblCharacterTableIndex->char_index_id;
        $colStrChar = $tempTblCharacterTableIndex->str_char;
        $colStatus = $tempTblCharacterTableIndex->status;
        $tempTblCharacterTableIndex = null;
        //
        $retValue = array();
        $counter = 0;
        foreach($dbResult as $row) {
            //DebugLog::log($row);
            $dataSet = array(
                self::TOKEN_ID   => $row[$colCharIndexId],
                self::TOKEN_NAME => $row[$colStrChar],
            );
            $retValue[] = $dataSet;
            $counter++;
        }
        if($counter < 1) {
            return array();
        }
        return $retValue;
    }

    private function getSelectSql() {
        //
        $tempTblCharacterTableIndex = new TblCharacterTableIndex();
        $colCharIndexId = $tempTblCharacterTableIndex->char_index_id;
        $colStrChar = $tempTblCharacterTableIndex->str_char;
        $colStatus = $tempTblCharacterTableIndex->status;
        $tempTblCharacterTableIndex = null;
        //
        $characterTableTokenTableName = DbQuizTable::characterTableTokenWithPrefix();
        //
        $sqlQueryBuilder = new SqlQueryBuilder();
        $sqlQuery = $sqlQueryBuilder
            ->select()
            ->from($characterTableTokenTableName)
            ->orderBy($colStrChar)
            ->build();
        //
        return $sqlQuery;
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
