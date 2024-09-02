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
use RzSDK\Database\Quiz\TblQuizSubjectQuery;
use RzSDK\Database\Quiz\TblQuizQuestionQuery;
?>
<?php
$dbType = DbType::SQLITE;
$databaseSchemaList = array(
    new TblLanguageQuery($dbType),
    new TblQuizCategoryIndexQuery($dbType),
    new TblQuizCategoryInfoQuery($dbType),
    new TblQuizSubjectQuery($dbType),
    new TblQuizQuestionQuery($dbType),
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
