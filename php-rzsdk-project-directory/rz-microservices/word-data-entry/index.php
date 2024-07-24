<?php
require_once("include.php");
?>
<?php
use RzSDK\Word\Navigation\SideLink;
use RzSDK\URL\SiteUrl;
use RzSDK\Log\DebugLog;
?>
<?php
$baseUrl = SiteUrl::getBaseUrl();
$projectBaseUrl = $baseUrl;
//echo $baseUrl;
$sideLink = (new SideLink($projectBaseUrl))->getSideLink();
?>
<?php
$string = "Hi [{word.there}] is a [{meaning.table}] value needs [{word.all}] data for [{next}] execution";
$pattern = "\[\{(.*?)\}\]"; //[{}]
$regexPattern = "/[\s+]?+{$pattern}/si";
if(preg_match_all($regexPattern, $string, $matchs)) {
    DebugLog::log($matchs);
} else{
    DebugLog::log("Not found");
}
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Home</title>
    <link href="<?= $projectBaseUrl; ?>/css/style.css"  rel="stylesheet" type="text/css" charset="utf-8">
</head>
<body>
<?php
$sideLink = (new SideLink())->getSideLink();
/*echo "<table cellspacing=\"0\" cellpadding=\"0\" width=\"100%\" border=\"1\" class=\"side-bar-menu\">"
    . "<tr>"
    . "<td>"
    . $sideLink
    . "</td>"
    . "</tr>"
    . "</table>"
    ." ";*/
?>
<table class="table-main-body-container">
    <tr>
        <td class="table-main-side-bar-menu"><?= $sideLink; ?></td>
        <td class="table-main-body-content-container">
            <table class="content-body-container">
                <tr>
                    <td></td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>
<!--<table cellpadding="1" cellspacing="1" border="1" valign="top">
    <tr>
        <td width="30px">LeftData1</td>
        <td>MidData1</td>
        <td width="100%">RightData1</td>
    </tr>
    <tr>
        <td>LeftData2</td>
        <td>MidData2</td>
        <td>RightData2</td>
    </tr>
</table>-->
