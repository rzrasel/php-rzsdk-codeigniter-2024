<?php
require_once("include.php");
?>
<?php
use RzSDK\DateTime\DateTime;
use RzSDK\URL\SiteUrl;
use RzSDK\Database\DbType;
use RzSDK\Log\DebugLog;
use RzSDK\Database\Schema\TblLanguageInfoQuery;
use RzSDK\Database\Schema\TblUserInfoQuery;
use RzSDK\Database\Schema\TblUserEmailQuery;
use RzSDK\Database\Schema\TblUserTokenInfoQuery;
?>
<?php
class SchemaTblLanguage {
    private $tableQuery;

    public function __construct() {
        $dbType = DbType::SQLITE;
        //$this->tableQuery = new TblLanguageQuery($dbType);
    }

    public function dropQuery() {
        return $this->tableQuery->dropQuery();
    }

    public function createQuery() {
        return $this->tableQuery->execute();
    }

    public function deleteQuery() {
        return $this->tableQuery->deleteQuery();
    }
}
//$tblLanguageSchema = new SchemaTblLanguage();
?>
<?php
$dbType = DbType::SQLITE;
$databaseSchemaList = array(
    new TblLanguageInfoQuery($dbType),
    new TblUserInfoQuery($dbType),
    new TblUserEmailQuery($dbType),
    new TblUserTokenInfoQuery($dbType),
);
?>
<br />
<br />
-- DATE CREATED: 2025-01-30, DATE MODIFIED: <?= DateTime::getCurrentDateTime("Y-m-d"); ?> VERSION: 1.1.1
<br />
<br />
<br />
<?php
foreach(array_reverse($databaseSchemaList) as $databaseSchema) {
    echo $databaseSchema->dropQuery();
    echo "<br />\n";
}
?>
<br />
<pre>
<?php
foreach($databaseSchemaList as $databaseSchema) {
    echo $databaseSchema->execute();
    echo "<br />";
    echo "<br />";
}
?>
</pre>
<?php
foreach(array_reverse($databaseSchemaList) as $databaseSchema) {
    echo $databaseSchema->deleteQuery();
    echo "<br />";
}
?>
<br />
<br />
<br />
<br />