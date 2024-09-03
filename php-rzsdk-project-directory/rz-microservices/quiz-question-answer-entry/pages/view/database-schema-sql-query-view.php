<?php
?>
<?php
?>
<?php
use RzSDK\DateTime\DateTime;
use RzSDK\Database\DbType;
use RzSDK\Database\Quiz\TblLanguageQuery;
use RzSDK\Database\Quiz\TblQuizCategoryIndexQuery;
use RzSDK\Database\Quiz\TblQuizCategoryInfoQuery;
use RzSDK\Database\Quiz\TblBookIndexQuery;
use RzSDK\Database\Quiz\TblBookNameQuery;
use RzSDK\Database\Quiz\TblBookInfoQuery;
use RzSDK\Database\Quiz\TblQuizSubjectQuery;
use RzSDK\Database\Quiz\TblQuizQuestionQuery;
use RzSDK\Database\Quiz\TblCharacterTableIndexQuery;
?>
<?php
$dbType = DbType::SQLITE;
$databaseSchemaList = array(
    new TblLanguageQuery($dbType),
    new TblQuizCategoryIndexQuery($dbType),
    //new TblQuizCategoryInfoQuery($dbType),
    new TblBookIndexQuery($dbType),
    new TblBookNameQuery($dbType),
    new TblBookInfoQuery($dbType),
    //new TblQuizSubjectQuery($dbType),
    //new TblQuizQuestionQuery($dbType),
    new TblCharacterTableIndexQuery($dbType),
);
?>
<?php
?>
                                    <table style="margin-left: 100px">
                                        <tr>
                                            <td>
                                                <br />
                                                <br />
                                                -- DATE CREATED: 2024-08-24, DATE MODIFIED: <?= DateTime::getCurrentDateTime("Y-m-d"); ?> VERSION: 0.0.1
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
                                            <br />
<?php
echo "<pre>";
foreach($databaseSchemaList as $databaseSchema) {
    echo $databaseSchema->execute();
    echo "<br />";
    echo "<br />";
}
echo "</pre>";
?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <br />
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
