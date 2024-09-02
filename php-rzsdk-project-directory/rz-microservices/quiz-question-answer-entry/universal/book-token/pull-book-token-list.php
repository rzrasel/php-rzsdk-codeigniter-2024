<?php
namespace RzSDK\Universal\Book\Token;
?>
<?php
use RzSDK\Database\SqliteConnection;
use RzSDK\SqlQueryBuilder\SqlQueryBuilder;
use RzSDK\Database\Space\DbQuizTable;
use RzSDK\Database\Quiz\TblBookIndex;
use RzSDK\Log\DebugLog;
?>
<?php
class PullBookTokenList {
    //
    private $name;
    private $isAllData = false;
    //
    public const BOOK_TOKEN_ID      = "id";
    public const BOOK_TOKEN_NAME    = "name";
    public const BOOK_TOKEN_SLUG    = "slug";
    //

    public function __construct() {
    }

    public function setName(string $name): self {
        $this->name = $name;
        return $this;
    }
    public function setIsAllData(bool $isAllData): self {
        $this->isAllData = $isAllData;
        return $this;
    }

    public function getBookToken() {
        $sqlQuery = $this->getSelectSql();
        //DebugLog::log($sqlQuery);
        $dbConn = $this->getDbConnection();
        $dbResult = $this->doRunDatabaseQuery($dbConn, $sqlQuery);
        if(empty($dbResult)) {
            return array();
        }
        //
        $tempTblLanguage = new TblBookIndex();
        $colBookTokenId = $tempTblLanguage->book_token_id;
        $colBookTokenName = $tempTblLanguage->book_token_name;
        $colBookTokenSlug = $tempTblLanguage->slug;
        $tempTblLanguage = null;
        //
        $retValue = array();
        $counter = 0;
        foreach($dbResult as $row) {
            //DebugLog::log($row);
            $dataSet = array(
                self::BOOK_TOKEN_ID   => $row[$colBookTokenId],
                self::BOOK_TOKEN_NAME => $row[$colBookTokenName],
                self::BOOK_TOKEN_SLUG => $row[$colBookTokenSlug],
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
        $tempTblBookIndex = new TblBookIndex();
        $colBookTokenName = $tempTblBookIndex->book_token_name;
        $colStatus = $tempTblBookIndex->status;
        $tempTblLanguage = null;
        //
        $bookTokenTableName = DbQuizTable::bookTokenWithPrefix();
        $sqlQueryBuilder = new SqlQueryBuilder();
        //
        $whereData = array();
        if(!empty($this->name)) {
            $whereData[$colBookTokenName] = $this->name;
        }
        if($this->isAllData) {
            $whereData[$colStatus] = true;
        }
        if(!empty($whereData)) {
            $sqlQuery = $sqlQueryBuilder
                ->select()
                ->from($bookTokenTableName)
                ->where($bookTokenTableName, $whereData)
                ->orderBy($colBookTokenName)
                ->build();
            return $sqlQuery;
        } else {
            $sqlQuery = $sqlQueryBuilder
                ->select()
                ->from($bookTokenTableName)
                ->orderBy($colBookTokenName)
                ->build();
            return $sqlQuery;
        }
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
