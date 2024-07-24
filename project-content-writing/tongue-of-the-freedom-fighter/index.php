<?php
require_once("../utils/include.php");
?>
<?php
use RzSDK\URL\SiteUrl;
use RzSDK\Site\Link\SiteLinkUtils;
?>
<?php
$baseUrl = SiteUrl::getBaseUrl();
$projectRootUrl = dirname($baseUrl);
//echo $baseUrl;
$siteLinkUtils = new SiteLinkUtils($projectRootUrl);
$siteLink = $siteLinkUtils->getSiteLink();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Freedom Speech</title>
    <link href="<?= $projectRootUrl; ?>/css/style.css"  rel="stylesheet" type="text/css" charset="utf-8">
</head>
<body>
<table cellspacing="0" cellpadding="0" width="100%" class="main-body-table">
    <tr>
        <td class="main-side-bar-menu"><?= $siteLink; ?></td>
        <td class="main-body-container">
            <table cellspacing="0" cellpadding="0" class="content-body-container">
                <tr>
                    <td></td>
                </tr>
            </table>
        </td>
    </tr>
</table>
    <!--<div class="div-body-table">
        <div class="div-body-table-lef"><?php /*= $siteLink; */?></div>
        <div class="div-body-table-right">
        </div>
    </div>-->
</body>
</html>