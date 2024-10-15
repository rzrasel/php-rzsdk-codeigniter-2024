<?php
require_once("../include.php");
?>
<?php
use RzSDK\URL\SiteUrl;
use RzSDK\Book\Navigation\Route\SideRouteNavigation;
use RzSDK\View\Html\View\MainHtmlView;
use RzSDK\Service\Listener\ServiceListener;
use RzSDK\Utils\Alert\Message\AlertMessageBox;
use RzSDK\Activity\Search\Word\Meaning\WordMeaningNewLineActivity;
use RzSDK\Log\DebugLog;
?>
<?php
$baseUrl = SiteUrl::getBaseUrl();
$pageUrlOnly = SiteUrl::getUrlOnly();
$projectBaseUrl = rtrim(dirname($baseUrl), "/");
/*echo $baseUrl;
echo "<br />";*/
$sideNavigation = (new SideRouteNavigation($projectBaseUrl))->getSideNavigation();
//echo $sideNavigation;
?>
<?php
$mainHtmlView = new MainHtmlView($projectBaseUrl);
$mainHtmlView->setPageTitle("Word Meaning New Line")
    ->setCss()
    ->setSideNavigation($sideNavigation)
    ->setPageHeader("Word Meaning New Line");
$mainHtmlView->renderTopView();
?>
    <table width="100%">
        <tr>
            <td height="50px">
<?php
$wordMeaningNewLineActivity = new WordMeaningNewLineActivity(
    new class implements ServiceListener {
        private AlertMessageBox $alertMessageBox;
        public function __construct() {
            $this->alertMessageBox = new AlertMessageBox();
        }
        public function onError($dataSet, $message) {
            //DebugLog::log($dataSet);
            //DebugLog::log($message);
            echo $this->alertMessageBox->build($message);
        }
        function onSuccess($dataSet, $message) {
            //DebugLog::log($message);
            echo $this->alertMessageBox->build($message);
            //header("Location: " . SiteUrl::getUrlOnly());
        }
    }
);
$wordMeaningNewLineActivity->execute($_POST);
?>
            </td>
        </tr>
    </table>
<?php
require_once("view/word-meaning-new-line-form.php");
?>
<?php
$mainHtmlView->renderBotomView();
?>