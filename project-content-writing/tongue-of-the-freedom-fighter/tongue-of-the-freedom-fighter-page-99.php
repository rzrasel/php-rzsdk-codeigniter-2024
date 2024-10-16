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
            <table cellspacing="0" cellpadding="0"  class="content-body-container">
                <tr class="page-title-header">
                    <td class="page-title-header"><?= !empty($_GET["page-title"]) ? $_GET["page-title"] : ""; ?></td>
                </tr>
                <tr>
                    <td>
                        <p>
                            আমি, ভবিষ্যত প্রজন্ম বলছি। আমি, ভবিষ্যত প্রজন্মের ইতিহাস বলছি। আমি, ভবিষ্যতের ইতিহাস বলছি। হ্যাঁ! আমি ইতিহাস। ভবিষ্যতের ইতিহাস। ভবিষ্যত প্রজন্মের ইতিহাস।
                        </p>
                        <p>
                            বাংলাদেশের ইতিহাস জানতে গিয়ে, পশ্চিমা ইতিহাসের সাথে মেলালে হবে না। তাতে, তালগোল পাকিয়ে দিশাহারা হতে হবে। এই উপমাহাদেশের, গণতন্ত্র, সমাজতন্ত্র, শৈরতন্ত্র-কে জানতে; পশ্চিমা বা প্রাশ্চাত্যের গণতন্ত্র, সমাজতন্ত্র বা শৈরতন্ত্র-কে জেনে, খুব একটা লাভ নেই। তা খুব একটা মিলবে-ও না। এই উপমাহাদেশের, গণতন্ত্র, সমাজতন্ত্র, শৈরতন্ত্র সম্পুর্ণ আলদা। আলদা বিশেষত্ব আছে।
                        </p>
                        <p>Another Topics</p>
                        <p>
                            বাংলাদেশের ইতিহাস জানতে হলে, আমাদেরকে ভারতবর্ষের অতীত ইতিহাস সম্পর্কে জানতে হবে। শুধু বাংলাদেশের ইতিহাস না, ভারতীয় ইতিহাস জানতে হলেও, আমাদেরকে ভারতবর্ষের অতীত ইতিহাস জানতে হবে। জানতে হবে এদেশের অতীত এবং বর্তমানের মানুষকে, মানুষের জীবনযাত্রা, ধ্যানধারণা। অতীত থেকে অতি প্রাচীন অতীত, সেখান থেকে বর্তমানের মানুষের মানশিকতা, ধর্ম, বিশ্বাস, ধর্মবিশ্বাসকে। জানতে হবে- আস্তিকতা, নাস্তিকতা, ঈশ্বরবাদ, নিরিশ্বরবাদ। জানতে হবে- হিন্দুত্ববাদ বা ব্রাহ্মণবাদ। হ্যাঁ, ব্রাহ্মণবাদ।
                        </p>
                        <p>
                            এ দেশের ইতিহাস, বা এই উপমাহাদেশের ইতিহাস জানতে হলে, অবশ্যই এবং অবশ্যই ব্রাহ্মণবাদকে জানতে হবে। অনেকেই হিন্দু, হিন্দুত্ববাদ বা ব্রাহ্মণবাদকে; মিলিয়ে মিশিয়ে এক করে ফেলে। আর, তার জন্য, এখানে বলে রাখা ভালো, হিন্দু, আর হিন্দুত্ববাদ বা ব্রাহ্মণবাদ কিন্তু এক নয়।
                        </p>
                        <p>
                            হিন্দুত্ববাদ বা ব্রাহ্মণবাদ একটা মানশিকতা। হিন্দুত্ববাদ বা ব্রাহ্মণবাদ- বর্ণবাদের মানশিকতা, জন্মগত শ্রেষ্টত্ববাদের মানশিকতা। হিন্দুত্ববাদ বা ব্রাহ্মণবাদ- মানুষকে গোলাম আর প্রভুত্বের মানশিকতা। মানুষকে মানশিক গোলামে পরিণত করার মানশিকতা। ব্রাহ্মণবাদ মানুষের কাছ থেকে মনুষত্ব কেড়ে নিয়ে, মানশিক গোলাম পরিণত করা মানশিকতা। বিনাবাক্যে, বিনাপ্রশ্নে প্রভুত্বের মানশিকতা। ব্রাহ্মণ্যবাদী, নিরেট গোমুর্খ হওয়ার পরও নিজেদেরকে সর্বশ্রেষ্ঠ দাবি করার মানশিকতা। ব্রাহ্মণ্যবাদ একটা মানশিক রোগ। যেখানে জ্ঞানশুন্য হয়েও, জন্মগতভাবে তারা নিজেদেরকে সর্বশ্রেষ্ঠ বলে দাবি করার মানশিকতা। আর, অন্যরা যতই জ্ঞানিগুনি হোক, তাদেরকে নিচ, অস্পর্সি দাবি করার মানশিকতা।
                        </p>
                        <p>
                            হিন্দুত্ববাদ বা ব্রাহ্মণবাদ না বুঝলে এ উপমাহাদেশের ইতিহাস বোঝা সম্ভব না। পৃথীবির শুরু থেকে আজ অবধি যতগুলো যুদ্ধ হয়েছে, যতগুলো গণহত্যা হয়েছে, ধর্ষণ হয়েছে, তার থেকে বহু গুন বেশি, মানুষ হত্যা, ধর্ষণ করেছে- হিন্দুত্ববাদী বা ব্রাহ্মণবাদী মানশিকতার মানুষগুলো। কিন্তু মজার ব্যাপার হচ্ছে, অন্য সব যুদ্ধে, গণহত্যায়, ধর্ষণে আপনি কাউকে না কাউকে দোষ দিতে পারবেন। দোষারপ করতে পারবেন। কিন্তু ব্রাহ্মণবাদীদের হত্যা, গণহত্যায়, ধর্ষণে তাদেরকে দোষারপ-ও করতে পারবেন না। কারণ, তারা অত্যন্ত কুটবুদ্ধি সম্পন্ন, কুটকৌশলি। ধর্মের নামে, প্রথমে মানশিক গোলাম বানানো হয়। আর নিজেরা বসে, শ্রষ্ঠত্বের আসনে, দেবতার আসনে। আর তাই তো, তারা স্বগর্বে বলে থাকে, তারা ব্রহ্মার মুখ থেকে জন্মেছে। আর বাকি আমরা সবাই- যারা নিচ
                        </p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>