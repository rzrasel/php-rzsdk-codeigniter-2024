<?php
require_once("include.php");
?>
<?php
use RzSDK\DateTime\DateTime;
use RzSDK\URL\SiteUrl;
use RzSDK\Database\Schema\TblParentChildInfoQuery;
/* use RzSDK\Database\Schema\TblQuestionInfoQuery;
use RzSDK\Database\Schema\TblAnswerInfoQuery; */
use RzSDK\Database\DbType;
use RzSDK\Log\DebugLog;
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
    new TblParentChildInfoQuery($dbType),
    /* new TblQuestionInfoQuery($dbType),
    new TblAnswerInfoQuery($dbType), */
);
?>
<br />
<br />
-- DATE CREATED: 2024-12-26, DATE MODIFIED: <?= DateTime::getCurrentDateTime("Y-m-d"); ?> VERSION: 0.0.1
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