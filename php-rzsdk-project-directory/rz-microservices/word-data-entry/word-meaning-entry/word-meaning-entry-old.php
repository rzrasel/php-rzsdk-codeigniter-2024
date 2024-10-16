<?php
namespace RzSDK\Word\Meaning\Entry;
?>
<?php
require_once("include.php");
?>
<?php
use RzSDK\URL\SiteUrl;
use RzSDK\Word\Navigation\SideLink;
use RzSDK\Model\HTTP\Request\Word\Meaning\Entry\Model\HttpWordMeaningEntryRequestModel;
use RzSDK\Service\Listener\ServiceListener;
use RzSDK\Data\Entry\Meaning\Activity\WordMeaningEntryActivity;
use RzSDK\Word\Meaning\Search\Word\Activity\WordSearchActivity;
use RzSDK\Log\DebugLog;
?>
<?php
$baseUrl = SiteUrl::getBaseUrl();
$projectBaseUrl = rtrim(dirname($baseUrl), "/");
//echo $baseUrl;
$sideLink = (new SideLink($projectBaseUrl))->getSideLink();
?>
<?php
class WordMeaningEntry {
    //
    public HttpWordMeaningEntryRequestModel $meaningEntryRequestModel;
    //

    public function __construct() {
        $this->meaningEntryRequestModel = new HttpWordMeaningEntryRequestModel();
    }

    public function execute() {
        //DebugLog::log($_POST);
        //
        $entryRequestQuery = $this->meaningEntryRequestModel->getQuery();
        foreach($entryRequestQuery as $key => $value) {
            if(array_key_exists($key, $_POST)) {
                $this->meaningEntryRequestModel->$key = $_POST[$key];
            } else {
                $this->meaningEntryRequestModel->$key = null;
            }
        }
        //
        if(empty($_POST)) {
            return;
        }
        if(!array_key_exists("meaning_entry_form", $_POST)) {
            return;
        }
        //DebugLog::log($this->meaningEntryRequestModel);
        //
        (new WordMeaningEntryActivity(
            new class($this) implements ServiceListener {
                private WordMeaningEntry $outerInstance;

                // Constructor to receive outer instance
                public function __construct($outerInstance) {
                    $this->outerInstance = $outerInstance;
                }

                public function onError($dataSet, $message) {
                    //DebugLog::log($dataSet);
                    DebugLog::log($message);
                }

                function onSuccess($dataSet, $message) {
                    //DebugLog::log($dataSet);
                    DebugLog::log($message);
                    $this->outerInstance->meaningEntryRequestModel->meaning = null;
                }
            }
        ))
            ->execute($this->meaningEntryRequestModel->getQuery());
    }
}
$wordMeaningEntry = new WordMeaningEntry();
$wordMeaningEntry->execute();
?>
<?php
$wordSearchActivity = new WordSearchActivity();
$wordSearchActivity
    ->setLimit(10)
    ->execute();
?>
<?php
$wordLanguage = $wordSearchActivity->wordSearchRequestModel->word_language;
$urlWordId = $wordSearchActivity->wordSearchRequestModel->url_word_id;
$searchWord = $wordSearchActivity->wordSearchRequestModel->search_word;
$urlWord = $wordSearchActivity->wordSearchRequestModel->url_word;
$wordLanguageOptions = $wordSearchActivity->getWordLanguageOptions($wordLanguage, "English");
$meaningLanguageOptions = $wordSearchActivity->getMeaningLanguageOptions("", "Bangla");
?>
<?php
$leftWidth = 15;
$midWidth = 2;
$rightWidth = 100 - ($leftWidth + $midWidth);
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Word Meaning Entry</title>
    <link href="<?= $projectBaseUrl; ?>/css/style.css"  rel="stylesheet" type="text/css" charset="utf-8">
</head>
<body>
<table cellspacing="0" cellpadding="0" width="100%" border="0" class="main-body-table">
    <tr>
        <td width="<?= $leftWidth; ?>%" class="side-bar-menu"><?= $sideLink; ?></td>
        <td width="<?= $midWidth; ?>%"></td>
        <td width="<?= $rightWidth; ?>%">
            <table width="100%" border="0" class="entry-form">
                <tr>
                    <td>Word Meaning Entry</td>
                </tr>
                <tr>
                    <td></td>
                </tr>
                <tr>
                    <td>
<?php
if(!empty($wordLanguage) && !empty($urlWordId) && !empty($urlWord)) {
?>
                        <form action="<?= $_SERVER["PHP_SELF"]; ?>" method="POST">
                            <table>
                                <tr>
                                    <td width="200px"></td>
                                    <td width="600px"></td>
                                </tr>
                                <tr>
                                    <td>Word: </td>
                                    <td>
                                        <input type="text" name="url_word" value="<?= $urlWord; ?>" placeholder="Search Word" required="required" readonly="readonly" />
                                    </td>
                                </tr>
                                <tr>
                                    <td>Meaning Language: </td>
                                    <td>
                                        <select name="meaning_language" required="required">
                                            <!--<option value="172157799761295096" selected="select">Bangla Language</option>
                                            <option value="172157831436333409">English Language</option>-->
                                            <?= $meaningLanguageOptions; ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Word Meaning: </td>
                                    <td>
                                        <input type="text" name="meaning" value="<?= $wordMeaningEntry->meaningEntryRequestModel->meaning; ?>" placeholder="Word Meaning" required="required" />
                                    </td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>
                                        <input type="hidden" name="meaning_entry_form" value="meaning_entry_form">
                                        <input type="hidden" name="word_language" value="<?= $wordLanguage; ?>">
                                        <input type="hidden" name="url_word_id" value="<?= $urlWordId; ?>">
                                        <input type="hidden" name="search_word" value="<?= $searchWord; ?>">
                                        <input type="hidden" name="url_word" value="<?= $urlWord; ?>">
                                    </td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td class="form-button">
                                        <button type="submit">Submit</button></td>
                                </tr>
                            </table>
                        </form>
<?php
}
?>
                    </td>
                </tr>
                <tr>
                    <td></td>
                </tr>
                <tr>
                    <td>
                        <form action="<?= $_SERVER["PHP_SELF"]; ?>" method="POST">
                            <table>
                                <tr>
                                    <td>Word Language: </td>
                                    <td>
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
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td class="form-button"><button type="submit">Search</button></td>
                                </tr>
                            </table>
                        </form>
                    </td>
                </tr>
                <tr>
                    <td><?= $wordSearchActivity->responseData; ?></td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>