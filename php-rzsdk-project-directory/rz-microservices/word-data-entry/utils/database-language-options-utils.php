<?php
namespace RzSDK\Utils\Database\Options\Language;
?>
<?php
use RzSDK\SqlQueryBuilder\SqlOrderType;
use RzSDK\SqlQueryBuilder\SqlQueryBuilder;
use RzSDK\DatabaseSpace\DbWordTable;
use RzSDK\Database\Word\TblLanguage;
use RzSDK\Database\SqliteConnection;
use RzSDK\Log\DebugLog;
?>
<?php
class DatabaseLanguageOptions {
    public function __construct() {}

    public function getLanguageIdByName($languageName = "English") {
        $tempTblLanguage = new TblLanguage();
        $lanIdCol = $tempTblLanguage->lan_id;
        $lanNameCol = $tempTblLanguage->lan_name;
        $statusCol = $tempTblLanguage->status;
        $tempDictionaryWord = null;
        //
        $languageTableName = DbWordTable::languageWithPrefix();
        $sqlQueryBuilder = new SqlQueryBuilder();
        $sqlQuery = $sqlQueryBuilder
            ->select()
            ->from($languageTableName)
            ->where("", array(
                $lanNameCol => $languageName,
                $statusCol => true,
            ))
            ->orderBy($lanNameCol, SqlOrderType::ASC)
            ->build();
        //DebugLog::log($sqlQuery);
        $dbConn = $this->getDbConnection();
        $dbResult = $this->doRunDatabaseQuery($dbConn, $sqlQuery);
        foreach($dbResult as $row) {
            return $row[$lanIdCol];
        }
        return null;
    }
    public function getLanguageOptions($languageId = "", $defaultLanguage = "English") {
        $dbResult = $this->getLanguageDatabaseResult();
        if(empty($dbResult)) {
            return "";
        }
        //
        $tempTblLanguage = new TblLanguage();
        $lanIdCol = $tempTblLanguage->lan_id;
        $lanNameCol = $tempTblLanguage->lan_name;
        $tempDictionaryWord = null;
        //
        $isDefaultFound = false;
        $dbDataSet = "";
        $counter = 0;
        foreach($dbResult as $row) {
            $counter++;
            $lan_id = $row[$lanIdCol];
            $lan_name = $row[$lanNameCol];
            if(empty($languageId)) {
                if($lan_name == $defaultLanguage) {
                    $languageId = $lan_id;
                }
            }
            if($languageId == $lan_id) {
                $isDefaultFound = true;
                $dbDataSet .= " "
                    . "<option value=\""
                    . $lan_id . "\""
                    . " selected=\"select\">"
                    . $lan_name . " Language"
                    . "</option>"
                    . "\n";
            } else {
                /*$dbDataSet[$row[$lanIdCol]] = array(
                    //$row[$lanIdCol] => $row[$lanNameCol],
                    "value=\"{$row[$lanIdCol]}\"" => $row[$lanNameCol],
                );*/
                $dbDataSet .= " "
                    . "<option value=\""
                    . $lan_id . "\""
                    . ">"
                    . $lan_name . " Language"
                    . "</option>"
                    . "\n";
            }
        }
        if($isDefaultFound) {
            $dbDataSet = " "
                . "<option value=\"\">"
                . "Select a Language"
                . "</option>"
                . "\n"
                . $dbDataSet;
        } else {
            $dbDataSet = " "
                . "<option value=\"\""
                . " selected=\"select\">"
                . "Select a Language"
                . "</option>"
                . "\n"
                . $dbDataSet;
        }
        /*if($counter < 1) {
            return "";
        }*/
        return $dbDataSet;
    }

    private function getLanguageDatabaseResult() {
        //
        $tempTblLanguage = new TblLanguage();
        $lanNameCol = $tempTblLanguage->lan_name;
        $statusCol = $tempTblLanguage->status;
        $tempDictionaryWord = null;
        //
        $languageTableName = DbWordTable::languageWithPrefix();
        $sqlQueryBuilder = new SqlQueryBuilder();
        $sqlQuery = $sqlQueryBuilder
            ->select()
            ->from($languageTableName)
            ->where("", array(
                $statusCol => true,
            ))
            ->orderBy($lanNameCol, SqlOrderType::ASC)
            ->build();
        //DebugLog::log($sqlQuery);
        $dbConn = $this->getDbConnection();
        $dbResult = $this->doRunDatabaseQuery($dbConn, $sqlQuery);
        return $dbResult;
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