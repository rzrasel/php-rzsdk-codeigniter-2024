<?php
require_once("include.php");
?>
<?php
use RzSDK\Word\Navigation\SideLink;
use RzSDK\URL\SiteUrl;
use RzSDK\Service\Listener\ServiceListener;
use RzSDK\Utils\Alert\Message\AlertMessageBox;
use RzSDK\Activity\Word\Meaning\Edit\WordMeaningEditActivity;
use RzSDK\Word\Meaning\Edit\Search\Word\Activity\WordSearchActivity;
use RzSDK\Shared\HTTP\Request\Parameter\RequestWordMeaningEditQueryModel;
use RzSDK\Module\Word\Meaning\Edit\WordMeaningEditModule;
use RzSDK\Log\DebugLog;
?>
<?php
$baseUrl = SiteUrl::getBaseUrl();
$projectBaseUrl = rtrim(dirname($baseUrl), "/");
//echo $baseUrl;
$sideLink = (new SideLink($projectBaseUrl))->getSideLink();
?>
<?php
?>
<?php
$wordMeaningEditUrl = SiteUrl::getUrlOnly();
$wordMeaningEntryUrl = dirname(dirname($wordMeaningEditUrl)) . "/word-meaning-entry/word-meaning-entry.php";
//$wordMeaningEditUrl = dirname(dirname($wordMeaningEditBaseUrl)) . "/word-meaning-edit/word-meaning-edit.php";
$wordSearchActivity = new WordSearchActivity();
$wordSearchActivity
    ->setLimit(10)
    ->setWordUrl($wordMeaningEntryUrl)
    ->setWordMeaningUrl($wordMeaningEditUrl)
    ->execute();
?>
<?php
$wordMeaningEditModule = new WordMeaningEditModule(
    new class implements ServiceListener {
        public function __construct() {
        }
        public function onError($dataSet, $message) {
            /*DebugLog::log($dataSet);
            DebugLog::log($message);*/
        }
        function onSuccess($dataSet, $message) {
            /*DebugLog::log($dataSet);
            DebugLog::log($message);*/
            //header("Location: " . SiteUrl::getUrlOnly());
        }
    }
);
$wordMeaningEditModule->execute();
?>
<?php
$word = $wordMeaningEditModule->wordMeaningEditQueryModel->word;
$meaningLanguage = $wordMeaningEditModule->wordMeaningEditQueryModel->meaning_language;
$meaningId = $wordMeaningEditModule->wordMeaningEditQueryModel->meaning_id;
$meaning = $wordMeaningEditModule->wordMeaningEditQueryModel->meaning;
//
$wordLanguage = $wordSearchActivity->wordSearchRequestModel->url_word_language;
$searchWord = $wordSearchActivity->wordSearchRequestModel->search_word;
$urlWordId = $wordSearchActivity->wordSearchRequestModel->url_word_id;
$urlWord = $wordSearchActivity->wordSearchRequestModel->url_word;
//
$wordLanguageOptions = $wordSearchActivity->getWordLanguageOptions("", "English");
$meaningLanguageOptions = $wordSearchActivity->getMeaningLanguageOptions($meaningLanguage);
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
                                <td class="table-entry-form-holder-page-header"></td>
                            </tr>
                            <tr>
                                <td class="response-message-section">
                                    <div class="response-message-box">
<?php
$wordMeaningEdit = new WordMeaningEditActivity(
    new class implements ServiceListener {
        private AlertMessageBox $alertMessageBox;
        public function __construct() {
            $this->alertMessageBox = new AlertMessageBox();
        }
        public function onError($dataSet, $message) {
            //DebugLog::log($dataSet);
            //DebugLog::log($message);
            echo $this->alertMessageBox->build($message);
        }
        function onSuccess($dataSet, $message) {
            //DebugLog::log($message);
            echo $this->alertMessageBox->build($message);
            //header("Location: " . SiteUrl::getUrlOnly());
        }
    }
);
$wordMeaningEdit->execute($wordMeaningEditModule->wordMeaningEditQueryModel);
//DebugLog::log($wordEdit->wordEditRequestModel);
?>
                                        <br />
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
<?php
if(!empty($word) && !empty($meaningLanguage) && !empty($meaning)) {
    require_once("view/word-meaning-edit-form-view.php");
}
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
