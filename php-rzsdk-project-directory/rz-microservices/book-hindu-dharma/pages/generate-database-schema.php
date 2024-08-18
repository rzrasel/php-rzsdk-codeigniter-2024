<?php
require_once("include.php");
?>
<?php
use RzSDK\URL\SiteUrl;
use RzSDK\Book\Navigation\Route\SideRoureNavigation;
use RzSDK\DateTime\DateTime;
use RzSDK\Database\Book\TblLanguageQuery;
use RzSDK\Database\Book\TblReligionQuery;
use RzSDK\Database\Book\TblAuthorQuery;
use RzSDK\Database\Book\TblBookQuery;
use RzSDK\Database\DbType;
use RzSDK\Log\DebugLog;
?>
<?php
$baseUrl = SiteUrl::getBaseUrl();
$projectBaseUrl = rtrim(dirname($baseUrl), "/");
//echo $baseUrl;
$sideNavigation = (new SideRoureNavigation($projectBaseUrl))->getSideNavigation();
//echo $sideNavigation;
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
class SchemaTblReligion {
    private $tableQuery;

    public function __construct() {
        $dbType = DbType::SQLITE;
        $this->tableQuery = new TblReligionQuery($dbType);
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
$tblReligionSchema = new SchemaTblReligion();
?>
<?php
class SchemaTblAuthor {
    private $tableQuery;

    public function __construct() {
        $dbType = DbType::SQLITE;
        $this->tableQuery = new TblAuthorQuery($dbType);
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
$tblAuthorSchema = new SchemaTblAuthor();
?>
<?php
class SchemaTblBook {
    private $tableQuery;

    public function __construct() {
        $dbType = DbType::SQLITE;
        $this->tableQuery = new TblBookQuery($dbType);
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
$tblBookSchema = new SchemaTblBook();
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
        <td class="table-main-side-bar-menu"><?= $sideNavigation; ?></td>
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
                                                -- DATE CREATED: 2024-08-17, DATE MODIFIED: <?= DateTime::getCurrentDateTime("Y-m-d"); ?> VERSION: 0.0.1
                                                <br />
                                                <br />
<?php
echo $tblLanguageSchema->dropQuery();
echo "<br />";
echo $tblReligionSchema->dropQuery();
echo "<br />";
echo $tblAuthorSchema->dropQuery();
echo "<br />";
echo $tblBookSchema->dropQuery();
?>
                                                <br />
                                                <br />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
<pre>
<?php
echo $tblLanguageSchema->createQuery();
echo "<br />";
echo "<br />";
echo $tblReligionSchema->createQuery();
echo "<br />";
echo "<br />";
echo $tblAuthorSchema->createQuery();
echo "<br />";
echo "<br />";
echo $tblBookSchema->createQuery();
?>
</pre>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <br />
<?php
echo $tblLanguageSchema->deleteQuery();
echo "<br />";
echo $tblReligionSchema->deleteQuery();
echo "<br />";
echo $tblAuthorSchema->deleteQuery();
echo "<br />";
echo $tblBookSchema->deleteQuery();
?>
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