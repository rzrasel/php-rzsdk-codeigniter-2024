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
                            বিশ্বাস চলে গেছে। এখানে সমাজ, তোমাকে আর বিশ্বাস করে না। এটা কিন্তু একটা জনবাদী অভ্যুত্থান। আর কোন সরকার-ই স্থায়ী হয় না, যদি পারস্পরিক আস্থা না থাকে। রাজনিতী, তার দলের উর্ধে গিয়ে রাস্তায় ঘুরছে; প্রচলিত যে ধারণা তাকে উলটে দিচ্ছে। আজকের যে স্বতঃস্ফূর্ত অভ্যুত্থান, এটার স্বকীয়তা এবং স্বতন্ত্রতা, আমারকে স্বীকার করতে হবে। এটার একটা নিজেস্ব চেহারা রয়েছে।
                        </p>
                        <p>
                            বিশ্বাস চলে গেছে। এখানে সমাজ, তোমাকে আর বিশ্বাস করে না। এটা কিন্তু একটা জনবাদী অভ্যুত্থান। আর কোন সরকার-ই স্থায়ী হয় না, যদি পারস্পরিক আস্থা না থাকে। রাজনিতী, তার দলের উর্ধে গিয়ে রাস্তায় ঘুরছে; প্রচলিত যে ধারণা তাকে উলটে দিচ্ছে।
                        </p>
                        <p>
                            <br />
                            <br />
                            <br />
                            <br />
                            <br />
                            <br />
                            <br />
                            <br />
                            <br />
                            মুক্তিযুদ্ধ ভার্সোন ২.০
                            <br />
                            <br />
                            <br />
 
 বিশ্বাস চলে গেছে। এখানে সমাজ, তোমাকে আর বিশ্বাস করে না। এটা কিন্তু একটা জনবাদী অভ্যুত্থান। আর কোন সরকার-ই স্থায়ী হয় না, যদি পারস্পরিক আস্থা না থাকে। রাজনিতী, তার দলের উর্ধে গিয়ে রাস্তায় ঘুরছে; প্রচলিত যে ধারণা তাকে উলটে দিচ্ছে। আজকের যে স্বতঃস্ফূর্ত অভ্যুত্থান, এটার স্বকীয়তা এবং স্বতন্ত্রতা, আমারকে স্বীকার করতে হবে। এটার একটা নিজেস্ব চেহারা রয়েছে।
 
 বিশ্বাস চলে গেছে। এখানে সমাজ, তোমাকে আর বিশ্বাস করে না। এটা কিন্তু একটা জনবাদী অভ্যুত্থান। আর কোন সরকার-ই স্থায়ী হয় না, যদি পারস্পরিক আস্থা না থাকে। রাজনিতী, তার দলের উর্ধে গিয়ে রাস্তায় ঘুরছে; প্রচলিত যে ধারণা তাকে উলটে দিচ্ছে। 
 
 এই রাজনিতীর ভাগ্য আর রাজনিতী বা আলাপ আলোচনায় নির্ধারিত হবে না। এটা নির্ধারিত হবে রাস্তায়। আর এতে নতুন নতুন স্লোগানের, জন্ম হয়েছে। এই যে, এদের যে কথা, যে কে বলছে রাজাকার? স্বৈরাচার। এর যে অনুরণন, যে আকর্ষণ, তা প্রচলিত যে ধারণা তাকে উলটে দিচ্ছে।
 
 এখনো তুমি দেশকে ভাগ করে রেখেছ, যে; কে মুক্তিযুদ্ধে অংশ নিয়েছে আর কে নেয়নি। এই ভাগাভাগি করে তুমি যদি এভাবেই সমাজকে চালাতে চাও তাহলে বেশ, আমি মেনে নিলাম, আমি রাজাকার। তুমি আমাকে গুলি করো। আর বলছে কে? রাজাকার? যে নিজে স্বৈরাচার। তা বলার কি তোমার হক আছে? তাহলে তোমার সরকারের বৈধতা কোথায়?
 
 এমনকি জনসাধারণের যে অসন্তোষ তাকেও সরকার সম্মান দিচ্ছে না। এরা খালি দেখছে, এটা বিএনপি, জামাত। দেখছে না, রাজনিতিটা দলের উর্ধে গিয়ে রাস্তায় ঘটছে। এটা বিএনপি তৈরি করে দিয়েছে বা জামাত তৈরি করে দিয়েছে, এই যদি আমাদের রাজনৈতিক ধারণা হয়, তখন সরকার অসহায় হয়ে যায়। ভাবে এটা কি হলো?
 
 এটা তো যুদ্ধ। ঘোষিত, অঘোষিত যুদ্ধ। এবং যে সামাজিক যুদ্ধটা মানুষের মনে ছিল, সেটা আজকে ফেটে বাইরে বেরিয়ে এসেছে। এর তীব্রতা তোমাকে হতচকিত করে দিয়েছে।
 
 
 
 
<br />
<br />
<br />
ভাইকে ছাড়াতে পুলিশের গাড়ি আটকে দিলো বোন | Quota Movment | High Court | News | Desh TV
<br />
https://youtu.be/Ql5XdzJYLBo
<br />
<br />
হাইকোর্টের সামনে শিক্ষার্থী-আইনজীবীদের সঙ্গে পুলিশের ধস্তাধস্তি | Quota Andolon 2024 | High Court
<br />
https://youtu.be/25jJPWbozd4
<br />
<br />
দর্শকের ফোনের জবাবে কী বললেন ব্যারিষ্টার সুমন? | News Hour Xtra | Quota Movement | Talk Show
<br />
https://youtu.be/cLYweZKLcrc
 
 End
                        </p>
                    </td>
                </tr>
                <tr><td></td></tr>
                <tr class="page-reference-footer">
                    <td class="page-reference-footer">
                        <a href="https://youtu.be/VPuk24YIpsk" target="_blank" >Bangladesh Students Protest 2024 । সংরক্ষণ, না হাসিনার শাসনের বিরুদ্ধে প্রতিবাদ?</a>
                        <a href="https://youtu.be/IzgUQ7b-amc" target="_blank" >Bangladesh Students Protest । ঢাকায় ঘরবন্দি হয়ে খালি সাইরেন শুনেছি: পবিত্র সরকার</a>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>