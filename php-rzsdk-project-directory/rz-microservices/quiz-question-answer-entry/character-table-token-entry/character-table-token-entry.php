<?php
require_once("../include.php");
?>
<?php
use RzSDK\URL\SiteUrl;
use RzSDK\Book\Navigation\Route\SideRouteNavigation;
use RzSDK\View\Html\View\MainHtmlView;
use RzSDK\Service\Listener\ServiceListener;
use RzSDK\Utils\Alert\Message\AlertMessageBox;
use RzSDK\Quiz\Activity\Character\Token\Entry\CharacterTableTokenEntryActivity;
use RzSDK\Universal\Character\Token\PullCharacterTokenList;
use RzSDK\Universal\Character\Token\BuildCharacterTokenSelectOptions;
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
$mainHtmlView->setPageTitle("Character Table Token Entry")
    ->setCss()
    ->setSideNavigation($sideNavigation)
    ->setPageHeader("Character Table Token Entry");
$mainHtmlView->renderTopView();
?>
<?php
/*$characterTokenSelectOptions = new BuildCharacterTokenSelectOptions();
$dataSet = $characterTokenSelectOptions->execute();
echo $dataSet;*/
//DebugLog::log($dataSet);
?>
<table width="100%">
    <tr>
        <td height="50px">
<?php
$characterTableTokenEntryActivity = new CharacterTableTokenEntryActivity(
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
$characterTableTokenEntryActivity->execute($_POST);
?>
        </td>
    </tr>
</table>
<?php
require_once("view/character-table-token-entry-from.php");
?>
<?php
$mainHtmlView->renderBotomView();
?>
<!--<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Home</title>
    <link href="<?php /*= $projectBaseUrl; */?>/css/style.css"  rel="stylesheet" type="text/css" charset="utf-8">
</head>
<body>
<table class="table-main-body-container">
    <tr>
        <td class="table-main-side-bar-menu"><?php /*= $sideNavigation; */?></td>
        <td class="table-main-body-content-container">
            <table class="content-body-container">
                <tr>
                    <td>
                        <table class="table-entry-form-holder">
                            <tr>
                                <td class="table-entry-form-holder-page-header">Language Entry</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>-->