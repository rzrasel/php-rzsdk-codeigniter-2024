<?php
namespace RzSDK\Universal\Language;
?>
<?php
use RzSDK\Database\SqliteConnection;
use RzSDK\SqlQueryBuilder\SqlQueryBuilder;
use RzSDK\Database\Space\DbQuizTable;
use RzSDK\Database\Quiz\TblLanguage;
use RzSDK\Log\DebugLog;
?>
<?php
class PullLanguageList {
    //
    private $name;
    private $isAllData = false;
    //
    public const LANGUAGE_ID    = "id";
    public const LANGUAGE_NAME  = "name";
    public const LANGUAGE_SLUG  = "slug";
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

    public function getLanguage() {
        $sqlQuery = $this->getSelectSql();
        //DebugLog::log($sqlQuery);
        $dbConn = $this->getDbConnection();
        $dbResult = $this->doRunDatabaseQuery($dbConn, $sqlQuery);
        if(empty($dbResult)) {
            return array();
        }
        //
        $tempTblLanguage = new TblLanguage();
        $colLanId = $tempTblLanguage->lan_id;
        $colLanName = $tempTblLanguage->lan_name;
        $colLanSlug = $tempTblLanguage->slug;
        $tempTblLanguage = null;
        //
        $retValue = array();
        $counter = 0;
        foreach($dbResult as $row) {
            //DebugLog::log($row);
            $dataSet = array(
                self::LANGUAGE_ID   => $row[$colLanId],
                self::LANGUAGE_NAME => $row[$colLanName],
                self::LANGUAGE_SLUG => $row[$colLanSlug],
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
        $tempTblLanguage = new TblLanguage();
        $colLanName = $tempTblLanguage->lan_name;
        $colStatus = $tempTblLanguage->status;
        $tempTblLanguage = null;
        //
        $languageTableName = DbQuizTable::languageWithPrefix();
        $sqlQueryBuilder = new SqlQueryBuilder();
        //
        $whereData = array();
        if(!empty($this->name)) {
            $whereData[$colLanName] = $this->name;
        }
        if($this->isAllData) {
            $whereData[$colStatus] = true;
        }
        if(!empty($whereData)) {
            $sqlQuery = $sqlQueryBuilder
                ->select()
                ->from($languageTableName)
                ->where($languageTableName, $whereData)
                ->orderBy($colLanName)
                ->build();
            return $sqlQuery;
        } else {
            $sqlQuery = $sqlQueryBuilder
                ->select()
                ->from($languageTableName)
                ->orderBy($colLanName)
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
