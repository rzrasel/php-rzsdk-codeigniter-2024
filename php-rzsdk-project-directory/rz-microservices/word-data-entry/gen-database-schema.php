<?php
require_once("include.php");
?>
<?php
use RzSDK\URL\SiteUrl;
use RzSDK\Database\DbType;
use RzSDK\Database\Word\TblLanguageQuery;
use RzSDK\Database\Word\DictionaryWordQuery;
use RzSDK\Database\Word\Meaning\DictionaryWordMeaningQuery;
use RzSDK\Word\Navigation\SideLink;
use RzSDK\DateTime\DateTime;
?>
<?php
?>
<?php
$baseUrl = SiteUrl::getBaseUrl();
$projectBaseUrl = $baseUrl;
//echo $baseUrl;
$sideLink = (new SideLink($projectBaseUrl))->getSideLink();
?>
<?php
class SchemaTblLanguage {
    private $tableQuery;

    public function __construct() {
        $dbType = DbType::SQLITE;
        $this->tableQuery = new TblLanguageQuery($dbType);
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
$tblLanguageSchema = new SchemaTblLanguage();
?>
<?php
class SchemaDictionaryWord {
    private $tableQuery;

    public function __construct() {
        $dbType = DbType::SQLITE;
        $this->tableQuery = new DictionaryWordQuery($dbType);
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
$dictionaryWordSchema = new SchemaDictionaryWord();
?>
<?php
class SchemaDictionaryWordMeaning {
    private $tableQuery;

    public function __construct() {
        $dbType = DbType::SQLITE;
        $this->tableQuery = new DictionaryWordMeaningQuery($dbType);
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
$wordMeaningSchema = new SchemaDictionaryWordMeaning();
?>
<?php
$leftWidth = 15;
$midWidth = 2;
$rightWidth = 100 - ($leftWidth + $midWidth);
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Database Schema</title>
    <link href="<?= $projectBaseUrl; ?>/css/style.css"  rel="stylesheet" type="text/css" charset="utf-8">
</head>
<body>
<table class="table-main-body-container">
    <tr>
        <td class="table-main-side-bar-menu"><?= $sideLink; ?></td>
        <td class="table-main-body-content-container">
            <table class="content-body-container">
                <tr>
                    <td>
                        <table class="table-entry-form-holder">
                            <tr>
                                <td class="table-entry-form-holder-page-header">Database Table Schema</td>
                            </tr>
                            <tr>
                                <td>
                                    <table style="margin-left: 100px">
                                        <tr>
                                            <td>
                                                <br />
                                                <br />
                                                -- DATE CREATED: 2024-07-20, DATE MODIFIED: <?= DateTime::getCurrentDateTime("Y-m-d"); ?> VERSION: 0.0.1
                                                <br />
                                                <br />
                                                <?= $wordMeaningSchema->dropQuery(); ?>
                                                <br />
                                                <?= $dictionaryWordSchema->dropQuery(); ?>
                                                <br />
                                                <?= $tblLanguageSchema->dropQuery(); ?>
                                                <br />
                                                <br />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
<pre>
<?= $tblLanguageSchema->createQuery(); ?>
<br />
<?= $dictionaryWordSchema->createQuery(); ?>
<br />
<?= $wordMeaningSchema->createQuery(); ?>
</pre>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <!--<br />-->
                                                <?= $wordMeaningSchema->deleteQuery(); ?>
                                                <br />
                                                <?= $dictionaryWordSchema->deleteQuery(); ?>
                                                <br />
                                                <?= $tblLanguageSchema->deleteQuery(); ?>
                                                <br />
                                                <br />
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>