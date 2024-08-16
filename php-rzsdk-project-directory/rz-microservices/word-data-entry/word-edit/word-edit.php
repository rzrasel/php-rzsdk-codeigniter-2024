<?php
?>
<?php
require_once("include.php");
?>
<?php
use RzSDK\URL\SiteUrl;
use RzSDK\Word\Navigation\SideLink;
use RzSDK\Word\Edit\Search\Word\Activity\WordSearchActivity;
use RzSDK\Service\Listener\ServiceListener;
use RzSDK\Utils\Alert\Message\AlertMessageBox;
use RzSDK\Module\HTTP\Request\Data\Word\Edit\WordEditRequestedDataModule;
use RzSDK\Shared\HTTP\Url\Parameter\GlobalUrlParameterModel;
use RzSDK\Log\DebugLog;
?>
<?php
/*$urlParameterModel = new GlobalUrlParameterModel();
$urlParameterModel->url_word = "test";
$urlParameterModel->url_word_id = "test";
$urlParameter = $urlParameterModel->getUrlParameters();
DebugLog::log($urlParameter);*/
?>
<?php
$baseUrl = SiteUrl::getBaseUrl();
$projectBaseUrl = rtrim(dirname($baseUrl), "/");
//echo $baseUrl;
$sideLink = (new SideLink($projectBaseUrl))->getSideLink();
?>
<?php
$wordSearchActivity = new WordSearchActivity();
$wordSearchActivity
    ->setLimit(10)
    ->execute();
?>
<?php
$wordLanguage = $wordSearchActivity->wordSearchRequestModel->url_word_language;
$searchWord = $wordSearchActivity->wordSearchRequestModel->search_word;
$urlWordId = $wordSearchActivity->wordSearchRequestModel->url_word_id;
$searchWord = "";
$urlWord = $wordSearchActivity->wordSearchRequestModel->url_word;
$wordLanguageOptions = $wordSearchActivity->getWordLanguageOptions("", "English");
//$meaningLanguageOptions = $wordSearchActivity->getMeaningLanguageOptions("", "Bangla");
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Word Edit</title>
    <link href="<?= $projectBaseUrl; ?>/css/style.css"  rel="stylesheet" type="text/css" charset="utf-8">
    <link href="<?= $projectBaseUrl; ?>/css/dictionary-word-list-table-style.css"  rel="stylesheet" type="text/css" charset="utf-8">
</head>
<body>
<table class="table-main-body-container">
    <tr>
        <td class="table-main-side-bar-menu"><?= $sideLink; ?></td>
        <td class="table-main-body-content-container">
            <table class="table-entry-form-holder">
                <tr>
                    <td>
                        <!--Body Container Table Start-->
                        <table class="table-entry-form-holder">
                            <tr>
                                <td class="table-entry-form-holder-page-header">Word Edit Form</td>
                            </tr>
                            <tr>
                                <td class="response-message-section">
                                    <div class="response-message-box">
<?php
$wordEdit = new WordEditRequestedDataModule(
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
        }
    }
);
$wordEdit->execute();
//DebugLog::log($wordEdit->wordEditRequestModel);
?>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
<?php
if(!empty($wordLanguage) && !empty($urlWordId) && !empty($urlWord)) {
    require_once("view/word-edit-form-view.php");
}
?>
                                </td>
                            </tr>
                            <tr>
                                <td>
<?php
require_once("view/word-edit-search-form-view.php");
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
                        <!--Body Container Table End-->
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>