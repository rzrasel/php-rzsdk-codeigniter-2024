<?php
?>
<?php
?>
<?php
use RzSDK\DateTime\DateTime;
use RzSDK\Database\DbType;
use RzSDK\Database\Book\TblLanguageQuery;
use RzSDK\Database\Book\TblReligionQuery;
use RzSDK\Database\Book\TblAuthorQuery;
use RzSDK\Database\Book\TblBookQuery;
use RzSDK\Database\Book\TblSectionQuery;
use RzSDK\Database\Book\TblSectionInfoQuery;
?>
<?php
$dbType = DbType::SQLITE;
$databaseSchemaList = array(
    new TblLanguageQuery($dbType),
    new TblReligionQuery($dbType),
    new TblAuthorQuery($dbType),
    new TblBookQuery($dbType),
    new TblSectionQuery($dbType),
    new TblSectionInfoQuery($dbType),
);
?>
<?php
?>
<table style="margin-left: 100px">
    <tr>
        <td>
            <br />
            <br />
            -- DATE CREATED: 2024-08-17, DATE MODIFIED: <?= DateTime::getCurrentDateTime("Y-m-d"); ?> VERSION: 0.0.1
            <br />
            <br />
            <br />
        </td>
    </tr>
    <tr>
        <td>
<?php
foreach($databaseSchemaList as $databaseSchema) {
    echo $databaseSchema->dropQuery();
    echo "<br />\n";
}
?>
        </td>
    </tr>
    <tr>
        <td>
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
        </td>
    </tr>
    <tr>
        <td>
<?php
foreach($databaseSchemaList as $databaseSchema) {
    echo $databaseSchema->deleteQuery();
    echo "<br />";
}
?>
        </td>
    </tr>
    <tr>
        <td>
            <br />
            <br />
            <br />
            <br />
            <!--<br />-->
            <br />
            <br />
            <br />
            <br />
        </td>
    </tr>
</table>
