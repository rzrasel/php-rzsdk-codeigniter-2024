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
    <title>Thinking Topics</title>
    <link href="<?= $projectRootUrl; ?>/css/style.css"  rel="stylesheet" type="text/css" charset="utf-8">
</head>
<body>
<table cellspacing="0" cellpadding="0" width="100%" class="main-body-table">
    <tr>
        <td class="main-side-bar-menu"><?= $siteLink; ?></td>
        <td class="main-body-container">
            <table cellspacing="0" cellpadding="0" class="content-body-container">
                <tr class="page-title-header">
                    <td class="page-title-header"><?= !empty($_GET["page-title"]) ? $_GET["page-title"] : ""; ?></td>
                </tr>
                <tr>
                    <td>
                        <div style="padding-left: 30px;">
                            <!--<p class="header">Thinking Topics</p>-->
                            <p>
                            <ol>
                                <li>দেশ ভাগের ইতিহাসে মুসলমানের দায় ও দায়বদ্ধতাঃ</li>
                                <li>১৯৭১ মুক্তিযুদ্ধের কারণঃ</li>
                                <li>১৯৭১ মুক্তিযোদ্ধার ঋণঃ</li>
                                <li>চেতনায় মুক্তি যুদ্ধঃ</li>
                                <li>মুক্তিযুদ্ধের ইতিহাস ও তৎপরবর্তি সময়ে মুক্তিযুদ্ধের ব্যবহারঃ</li>
                                <li>মুক্তি যুদ্ধ কি ধর্ম (মুক্তিযুদ্ধইজম) বা তার সমপর্যায়েরঃ</li>
                                <li>মুক্তি যোদ্ধাদের ইতিহাস কেন ১৯৭১ এই আটকে থাকেঃ</li>
                                <li>১৯৭১ এর মুক্তিযোদ্ধা, ২০২৪ এর রাজাকারঃ</li>
                            </ol>
                            </p>
                        </div>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
<!--<div class="div-body-table">
    <div class="div-body-table-lef"><?php /*= $siteLink; */?></div>
    <div class="div-body-table-right">
        <p class="header">Thinking Topics</p>
        <p>
            <ol>
                <li>দেশ ভাগের ইতিহাসে মুসলমানের দায় ও দায়বদ্ধতাঃ</li>
                <li>১৯৭১ মুক্তিযুদ্ধের কারণঃ</li>
                <li>১৯৭১ মুক্তিযোদ্ধার ঋণঃ</li>
                <li>চেতনায় মুক্তি যুদ্ধঃ</li>
                <li>মুক্তিযুদ্ধের ইতিহাস ও তৎপরবর্তি সময়ে মুক্তিযুদ্ধের ব্যবহারঃ</li>
                <li>মুক্তি যুদ্ধ কি ধর্ম (মুক্তিযুদ্ধইজম) বা তার সমপর্যায়েরঃ</li>
                <li>মুক্তি যোদ্ধাদের ইতিহাস কেন ১৯৭১ এই আটকে থাকেঃ</li>
                <li>১৯৭১ এর মুক্তিযোদ্ধা, ২০২৪ এর রাজাকারঃ</li>
            </ol>
        </p>
    </div>
</div>-->
</body>
</html>