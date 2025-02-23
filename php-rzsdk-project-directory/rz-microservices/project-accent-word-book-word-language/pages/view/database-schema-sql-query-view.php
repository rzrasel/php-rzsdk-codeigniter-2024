<?php
?>
<?php
?>
<?php
use RzSDK\DateTime\DateTime;
use RzSDK\Database\DbType;
use RzSDK\Database\Schema\TblWordMappingQuery;
?>
<?php
$dbType = DbType::SQLITE;
$databaseSchemaList = array(
    new TblWordMappingQuery($dbType),
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
