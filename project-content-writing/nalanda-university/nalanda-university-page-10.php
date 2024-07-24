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
                        <p>[0-9|:|,|\-|>]+<br />[\s|\r\n|\r|\n]+</p>
                        <!--<p class="header">নালন্দা ইউনিভার্সিটি</p>-->
                        <p>
                            সোশ্যাল মিডিয়ায় প্রচারিত সবচেয়ে বড়, সর্বাধিক প্রচারিত, প্রতিষ্ঠিত ও জনপ্রিয় মিথ। এই মিথের দ্বিতীয় উৎস ধর্মস্বামীনের বিবরণ থেকে পাওয়া যায়। ধর্মস্বামীন, একজন তিব্বতীয়, সন্ন্যাসী। তিনি, ১২৩৪ খ্রিস্টাব্দে ভারতে এসেছিলেন। তিনি নালন্দা ছাড়াও, আরও অনেক বিহারে গিয়েছিলেন। ধর্মস্বামীন অনেক কিছু-ই লিখেছেন, কিন্তু একটা জিনিস লেখেননি, আর তা হলো; বখতিয়ার খিলজি নালন্দা আক্রমণ করেছে। ধর্মস্বামীনের পুরো বইয়ের, কোথাও উল্লেখ নেই যে, বখতিয়ার খিলজি নালন্দা'তে আক্রমণ করেছিলেন। উভয় সূত্র, মিনহাজ-ই-সিরাজের "ত্ববাকত-ই-নাসিরী"; এবং ধর্মস্বামীনের বই, উভয়ই; বখতিয়ার খিলজি'র- ১১৯৯ খ্রিস্টাব্দের নালন্দা'তে আক্রমণ মিথের সবথেকে কাছাকাছি সময়ের বা নিকটতম সময়ের উৎস।
                        </p>
                        <p>
                            আর এ দুই সূত্র-ই, নালন্দা ধ্বংসের জন্য, বখতিয়ার খিলজিকে; দ্বায়ী বা দোষারোপ করেনি। আর, বখতিয়ার খিলজি- যদি কখনও নালন্দা'তে আক্রমণ করতেন; তাহলে ধর্মস্বামীন তো তার বইয়ে উল্লেখ করতেন। কেননা ওই সময়, বখতিয়ার খিলজির সেই আক্রমণের স্মৃতি- নালন্দার লোকদের সৃত্মিতে তাজা থাকত। স্বরণে থাকত।

                            //তিনি ধ্বংসের জন্য দায়ী করেন না যদি বখতিয়ার কখনও নালন্দা আক্রমণ করতেন, তবে ধর্ম স্বামী তাকে বলে দিতেন কারণ সেই আক্রমণের স্মৃতি নালন্দার লোকদের মনে তাজা হয়ে থাকত যখন তিনি নালন্দায় ছিলেন তখন আমরা এই বিষয়ে বিস্তারিত আলোচনা করব।
                        </p>
                        <p>//208</p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>