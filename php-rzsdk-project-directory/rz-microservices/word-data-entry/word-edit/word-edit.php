<?php
?>
<?php
require_once("include.php");
?>
<?php
use RzSDK\URL\SiteUrl;
use RzSDK\Word\Navigation\SideLink;
use RzSDK\Word\Edit\Search\Word\Activity\WordSearchActivity;
use RzSDK\Log\DebugLog;
?>
<?php
?>
<?php
$wordSearchActivity = new WordSearchActivity();
$wordSearchActivity
    ->setLimit(10)
    ->execute();
?>
<?php
/*$wordLanguage = $wordSearchActivity->wordSearchRequestModel->url_word_language;
$urlWordId = $wordSearchActivity->wordSearchRequestModel->url_word_id;
$searchWord = $wordSearchActivity->wordSearchRequestModel->search_word;*/
$searchWord = "";
//$urlWord = $wordSearchActivity->wordSearchRequestModel->url_word;
$wordLanguageOptions = $wordSearchActivity->getWordLanguageOptions("", "English");
//$meaningLanguageOptions = $wordSearchActivity->getMeaningLanguageOptions("", "Bangla");
?>
<?php
$baseUrl = SiteUrl::getBaseUrl();
$projectBaseUrl = rtrim(dirname($baseUrl), "/");
//echo $baseUrl;
$sideLink = (new SideLink($projectBaseUrl))->getSideLink();
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
/*$wordEntry = new WordEntry(
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
$wordEntry->execute();*/
?>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <form action="<?= $_SERVER["PHP_SELF"]; ?>" method="POST">
                                        <table class="table-entry-form-field-container">
                                            <tr><td height="20px"></td><td></td><td></td></tr>
                                            <tr><td width="150px"></td><td width="250px" style="padding: 0px 15px;"></td><td width="250px"></td></tr>
                                            <!--<tr>
                                                <td></td><td><input type="button" value="Submit" /></td>
                                            </tr>-->
                                            <tr>
                                                <td>Word Language: </td>
                                                <td style="padding: 0px 15px;">
                                                    <select name="word_language" required="required">
                                                        <!--<option value="172157799761295096">Bangla Language</option>
                                                        <option value="172157831436333409" selected="select">English Language</option>-->
                                                        <?= $wordLanguageOptions; ?>
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="text" name="search_word" value="<?= $searchWord; ?>" required="required" placeholder="Search Word">
                                                </td>
                                            </tr>
                                            <tr><td height="20px"></td><td></td><td></td></tr>
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td class="form-button"><button type="submit" class="button-6">Search</button></td>
                                            </tr>
                                        </table>
                                    </form>
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