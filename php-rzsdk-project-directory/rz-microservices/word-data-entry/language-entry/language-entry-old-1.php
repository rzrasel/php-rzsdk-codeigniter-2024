<?php
namespace RzSDK\Word\Entry;
?>
<?php
require_once("include.php");
?>
<?php
use RzSDK\URL\SiteUrl;
use RzSDK\Word\Navigation\SideLink;
use RzSDK\Data\Entry\Activity\LanguageEntryActivity;
use RzSDK\Service\Listener\ServiceListener;
use RzSDK\Log\DebugLog;
?>
<?php
//$languageEntryActivity = new LanguageEntryActivity($_POST);
?>
<?php
class LanguageEntry {
    public function __construct() {
        //$this->generateUI();
    }

    public function execute() {
        if(empty($_POST)) {
            return;
        }
        (new LanguageEntryActivity(
            new class implements ServiceListener {
                public function onError($dataSet, $message) {
                    //DebugLog::log($dataSet);
                    DebugLog::log($message);
                }

                function onSuccess($dataSet, $message) {
                    DebugLog::log($message);
                }
            }
        ))
            ->execute($_POST);
    }

    private function generateUI() {
        $baseUrl = rtrim(dirname(SiteUrl::getBaseUrl()), "/");
        //echo $baseUrl;
        $sideLink = (new SideLink($baseUrl))->getSideLink();
        $uiTableData = "<table cellpadding=\"0\" cellspacing=\"0\">"
            . "<tr>"
            . "<td width=\"10%\">"
            . $sideLink
            . "</td>"
            . "<td width=\"2%\">"
            . "</td>"
            . "<td width=\"88%\">"
            . " ";
        $uiTableData .= "<table cellpadding=\"0\" cellspacing=\"0\" valign=\"top\">"
            . "<tr>"
            . "<td>Language Data Entry</td>"
            . "</tr>"
            . "</table>"
            . " ";
        $uiTableData .= "</td>"
            . "</tr>"
            . "</table>"
            . " ";
        echo $uiTableData;
    }

    private function getPageHeader() {
    }
}
?>
<?php
(new LanguageEntry())->execute();
//new LanguageEntry();
//<button class="_42ft _5upp _50zy clear uiTypeaheadCloseButton _50-0 _50z-" title="Remove" onclick="" type="button" id="u_0_bj">Remove</button><input autocomplete="off" class="hiddenInput" type="hidden">
?>
<?php
$baseUrl = SiteUrl::getBaseUrl();
$projectBaseUrl = rtrim(dirname($baseUrl), "/");
//echo $baseUrl;
$sideLink = (new SideLink($projectBaseUrl))->getSideLink();
?>
<?php
$leftWidth = 15;
$midWidth = 2;
$rightWidth = 100 - ($leftWidth + $midWidth);
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Language Entry</title>
    <link href="<?= $projectBaseUrl; ?>/css/style.css"  rel="stylesheet" type="text/css" charset="utf-8">
</head>
<body>
<table cellspacing="0" cellpadding="0" width="100%" border="0" class="main-body-table">
    <tr>
        <td width="<?= $leftWidth; ?>%" class="side-bar-menu"><?= $sideLink; ?></td>
        <td width="<?= $midWidth; ?>%"></td>
        <td width="<?= $rightWidth; ?>%">
            <table width="100%" border="0" class="entry-form">
                <tr>
                    <td>Language Entry</td>
                </tr>
                <tr>
                    <td>
                        <form action="<?= $_SERVER["PHP_SELF"]; ?>" method="POST">
                            <table>
                                <tr>
                                    <td>Language: </td>
                                    <td class="form-input-td">
                                        <input type="text" name="language" value="" placeholder="Language Name" required="required" />
                                    </td>
                                </tr>
                                <tr><td height="30px"></td></tr>
                                <!--<tr>
                                    <td></td><td><input type="button" value="Submit" /></td>
                                </tr>-->
                                <tr>
                                    <td></td>
                                    <td class="form-button"><button type="submit">Submit</button></td>
                                </tr>
                            </table>
                        </form>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>