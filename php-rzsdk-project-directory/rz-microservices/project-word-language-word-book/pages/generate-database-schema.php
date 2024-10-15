<?php
require_once("include.php");
?>
<?php
use RzSDK\URL\SiteUrl;
use RzSDK\Book\Navigation\Route\SideRouteNavigation;
use RzSDK\Database\Book\TblLanguageQuery;
use RzSDK\Database\DbType;
use RzSDK\View\Html\View\MainHtmlView;
use RzSDK\Log\DebugLog;
?>
<?php
$baseUrl = SiteUrl::getBaseUrl();
$projectBaseUrl = rtrim(dirname($baseUrl), "/");
//echo $baseUrl;
$sideNavigation = (new SideRouteNavigation($projectBaseUrl))->getSideNavigation();
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
//$tblLanguageSchema = new SchemaTblLanguage();
?>
<?php
$mainHtmlView = new MainHtmlView($projectBaseUrl);
$mainHtmlView->setPageTitle("Database Schema")
    ->setCss()
    ->setSideNavigation($sideNavigation)
    ->setPageHeader("Database Table Schema");
$mainHtmlView->renderTopView();
?>
<?php
require_once("view/database-schema-sql-query-view.php");
?>
<?php
$mainHtmlView->renderBotomView();
?>