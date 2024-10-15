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
                    <td class="page-title-header">
                        <?= !empty($_GET["page-title"]) ? $_GET["page-title"] : ""; ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p>[0-9|:|,|\-|>]+<br />[\s|\r\n|\r|\n]+</p>
                        <!--<p class="header">নালন্দা ইউনিভার্সিটি</p>-->
                        <p>
                            চাইলে আপনি স্ক্রিনশট থেকে মিলিয়ে নিতে পারেন। সম্পদ আহরণের জন্য তিনি বিহারের দুর্গ আক্রমণ করেন। যেমনটা সমস্ত রাজা, বাদশাহ'রা সচরাচর করতেন। টাকা পয়সা, ঘোড়া ইত্যাদির জন্য। মিনহাজ-ই-সিরাজ আরও বলেন, এই হামলায় অনেক মানুষের মৃত্যু হয়, এবং কিলা-ই-বিহারে অনেক প্রকারের কিতাব বা বই পাওয়া যায়। পরে জিজ্ঞাসা করে জানতে পারে, এই জায়গা পাঠ্যস্থান যাকে স্কুল বা বিহার বলা হয়ে থাকে। মুলত বিহার, যেখানে বৌদ্ধ ভিক্ষু বা সন্ন্যাসীদের বসবাস। শুধু এতটুকুই।
                        </p>
                        <p>
                            নালন্দার নাম, কোথাও উল্লেখ নেই। এর পূর্বের একটি ভাগে মিনহাজ-ই-সিরাজ - এই বিহারের নামও উল্লেখ করেছেন। আউদন্ড বিহার। যাকে যদুনাথ সরকার, ওদন্ডপুরা-বিহার বা ওদন্তপুরী নামে উল্লেখ করেন। যাই হোক, ওদন্ডপুরা-বিহার বা ওদন্তপুরীর সম্পর্ক আরও অনেক ইতিহাসবিদেরা করেছেন। কিন্তু আমি এখানে জেনে-বুঝেই যদুনাথ সরকারের নাম উল্লেখ করছি, কেননা যদুনাথ সোশ্যাল মিডিয়া ইউনিভার্সিটির ভক্ত ও অন্ধভক্তদের কাছে অত্যন্ত প্রিয় ইতিহাসবিদ। তো ভেবে দেখুন, তাদের প্রিয়পাত্রের কাছ থেকে জানলে; তাদের বিশ্বাস করতে সহজ হবে।--142
                        </p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>