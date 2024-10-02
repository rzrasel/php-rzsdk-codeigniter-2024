<?php
namespace RzSDK\Universal\Search\Word\Helper;
?>
<?php
use RzSDK\Database\Space\DbTableListing;
use RzSDK\Database\Schema\TblWordMapping;
use RzSDK\SqlQueryBuilder\SqlQueryBuilder;
use RzSDK\Database\SqliteConnection;
use RzSDK\Utils\String\StringHelper;
use RzSDK\Log\DebugLog;
?>
<?php
class WordSearchHelper {
    //
    private $searchWord = "";
    private $dbResult;
    //

    protected function setSearchingWord($word) {
        $word = strtolower($word);
        $word = StringHelper::toSingleSpace($word);
        $this->searchWord = $word;
        return $this;
    }

    protected function executeSearch() {
        $sqlQuery = $this->getSearchSql();
        $dbConn = $this->getDbConnection();
        $this->dbResult = $this->doRunDatabaseQuery($dbConn, $sqlQuery);
        return $this->dbResult;
    }

    protected function closeDbResult() {
        $this->dbResult = null;
    }

    protected function getSearchSql() {
        //
        $tempTblWordMapping = new TblWordMapping();
        $colWordId = $tempTblWordMapping->word_id;
        $colWord = $tempTblWordMapping->word;
        $colPronunciation = $tempTblWordMapping->pronunciation;
        $colMeaning = $tempTblWordMapping->meaning;
        $tempTblWordMapping = null;
        //
        $whereData = array(
            $colWord => "{LIKE}{$this->searchWord}%",
        );
        //
        $wordMappingTableName = DbTableListing::wordMappingWithPrefix();
        $sqlQueryBuilder = new SqlQueryBuilder();
        $sqlQuery = $sqlQueryBuilder
            ->select()
            ->from($wordMappingTableName)
            ->where($wordMappingTableName, $whereData)
            ->orderBy($colWord)
            ->build();
        //DebugLog::log($sqlQuery);
        return $sqlQuery;
    }

    protected function doRunDatabaseQuery($dbConn, $sqlQuery) {
        return $dbConn->query($sqlQuery);
    }

    protected function getDbConnection() {
        $dbFullPath = "../" . DB_PATH . "/" . DB_FILE;
        return new SqliteConnection($dbFullPath);
    }
}
?>