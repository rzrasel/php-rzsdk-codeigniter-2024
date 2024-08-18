<?php
require_once("include.php");
?>
<?php
use RzSDK\Word\Navigation\SideLink;
use RzSDK\URL\SiteUrl;
use RzSDK\Word\Meaning\Edit\Search\Word\Activity\WordSearchActivity;
use RzSDK\Log\DebugLog;
?>
<?php
$baseUrl = SiteUrl::getBaseUrl();
$projectBaseUrl = rtrim(dirname($baseUrl), "/");
//echo $baseUrl;
$sideLink = (new SideLink($projectBaseUrl))->getSideLink();
?>
<?php
$wordUrl = SiteUrl::getUrlOnly();
$wordMeaningUrl = dirname(dirname($wordUrl)) . "/word-meaning-edit/word-meaning-edit.php";
$wordSearchActivity = new WordSearchActivity();
$wordSearchActivity
    ->setLimit(10)
    ->setWordUrl($wordUrl)
    ->setWordMeaningUrl($wordMeaningUrl)
    ->execute();
?>
<?php
$wordLanguageOptions = $wordSearchActivity->getWordLanguageOptions("", "English");
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Home</title>
    <link href="<?= $projectBaseUrl; ?>/css/style.css"  rel="stylesheet" type="text/css" charset="utf-8">
    <link href="<?= $projectBaseUrl; ?>/css/dictionary-word-list-table-style.css"  rel="stylesheet" type="text/css" charset="utf-8">
</head>
<body>
<table class="table-main-body-container">
    <tr>
        <td class="table-main-side-bar-menu"><?= $sideLink; ?></td>
        <td class="table-main-body-content-container">
            <table class="content-body-container">
                <tr>
                    <td>
                        <!--Body Container Table Start-->
                        <table class="table-entry-form-holder">
                            <tr>
                                <td class="table-entry-form-holder-page-header">Word Meaning Edit</td>
                            </tr>
                            <tr>
                                <td class="table-entry-form-holder-page-header"><?= DebugLog::log($_REQUEST); ?></td>
                            </tr>
                            <tr>
                                <td>
<?php
/*if(!empty($wordLanguage) && !empty($urlWordId) && !empty($urlWord)) {
    require_once("view/word-meaning-edit-form-view.php");
}*/
require_once("view/word-meaning-edit-form-view.php");
?>
                                </td>
                            </tr>
                            <tr><td height="20px"></td></tr>
                            <tr>
                                <td>
<?php
require_once("view/word-meaning-edit-search-form-view.php");
?>
                                </td>
                            </tr>
                            <tr><td height="20px"></td></tr>
                            <tr>
                                <td>
                                    <table width="100%">
                                        <tr>
                                            <td><?= $wordSearchActivity->responseData; ?></td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>
