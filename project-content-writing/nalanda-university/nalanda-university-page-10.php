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
    <title>Nalanda University</title>
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
                        <!--<p class="header">নালন্দা ইউনিভার্সিটি</p>-->
                        <p>
                            বড়ো মাপের একজন ভিলেন আসলো, এসে আগুন দিলো; মানুষের মধ্যে বিশৃঙ্খলার শুরু, তারপর চারদিকে নিঃশ্চুপ, নিস্তব্ধতা। হচ্ছেটা কি? লক্ষ্মণ! লড়, ভাই ওর সাথে। সোশ্যাল মিডিয়ায় প্রচারিত সবচেয়ে বড়, সর্বাধিক প্রচারিত, প্রতিষ্ঠিত ও জনপ্রিয় মিথ। দৈবিক ভাবে প্রাপ্ত, দৈবিক মিথ। বিধাতার দেওয়া রাজকীয় মিথ। যে, তারা শপথ নিয়ে মিথ্যা বলে; মানুষকে বিভ্রান্ত করবে, সিদ্ধি প্রাপ্ত হয়ে; অবশ্যই এর বদলা নেবে। ইখতিয়ারউদ্দীন মুহাম্মাদ বখতিয়ার খলজী নালন্দা ধ্বংস করেন। এক ধাক্কা, আরো মারো; এই মিথকেও ভেঙে ফেলো। কোন এক সকালে উঠে, মুহাম্মাদ বখতিয়ার খলজী মর্নিং ওয়াক করতে বের হয়; মর্নিং ওয়াক করতে করতে বাংলায় পৌঁছায়, তার মিনিট খানিক পর; বেঙ্গল- মুহাম্মাদ বখতিয়ার খলজী'র।
                        </p>
                        <p>
                        </p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>