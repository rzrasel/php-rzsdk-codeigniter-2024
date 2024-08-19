<?php
use RzSDK\Service\Listener\ServiceListener;
use RzSDK\Utils\Alert\Message\AlertMessageBox;
use RzSDK\Module\HTTP\Request\Data\Word\Edit\WordEditRequestedDataModule;
use RzSDK\Word\Edit\Search\Word\Activity\WordSearchActivity;
use RzSDK\Word\Edit\Activity\WordEditActivity;
use RzSDK\URL\SiteUrl;
use RzSDK\Log\DebugLog;
?>
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
            //echo $this->alertMessageBox->build($message);
        }

        function onSuccess($dataSet, $message) {
            //DebugLog::log($message);
            //echo $this->alertMessageBox->build($message);
        }
    }
);
$wordEdit->execute();
//DebugLog::log($wordEdit->wordEditRequestModel);
?>
<?php
$wordEditActivity = (new WordEditActivity(
        new class implements ServiceListener {
            private AlertMessageBox $alertMessageBox;
            public function __construct() {
                $this->alertMessageBox = new AlertMessageBox();
            }

            public function onError($dataSet, $message) {
                //DebugLog::log($dataSet);
                //DebugLog::log($message);
                echo $this->alertMessageBox->build($message);
                echo "<br/>";
                echo "<br/>";
            }

            function onSuccess($dataSet, $message) {
                //DebugLog::log($message);
                echo $this->alertMessageBox->build($message);
                echo "<br/>";
                echo "<br/>";
                //header("Location: " . SiteUrl::getUrlOnly());
            }
        }
))->execute($wordEdit->wordEditRequestModel);
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
<form action="<?= SiteUrl::getFullUrl(); ?>" method="POST">
    <table class="table-entry-form-field-container">
        <!--<tr>
            <td class="table-entry-form-field-left">Word: </td>
            <td class="table-entry-form-field-right">
                <input type="text" name="url_word" value="<?php /*= $urlWord; */?>" placeholder="Search Word" required="required" readonly="readonly" />
            </td>
        </tr>-->
        <tr>
            <td class="table-entry-form-field-left">Language: </td>
            <td class="table-entry-form-field-right">
                <select name="language" required="required">
                    <!--<option value="172157799761295096">Bangla</option>
                    <option value="172157831436333409" selected="select">English</option>-->
                    <?= $wordEdit->getLanguageOptions(); ?>
                </select>
            </td>
        </tr>
        <tr><td height="20px"></td><td></td></tr>
        <tr>
            <td>Word: </td>
            <td>
                <input type="text" name="word" value="<?= $wordEdit->wordEditRequestModel->word; ?>" placeholder="Dictionary Word" required="required" />
            </td>
        </tr>
        <tr><td height="20px"></td><td></td></tr>
        <tr>
            <td>Pronunciation: </td>
            <td>
                <input type="text" name="pronunciation" value="<?= $wordEdit->wordEditRequestModel->pronunciation; ?>" placeholder="Word Pronunciation" required="required" />
            </td>
        </tr>
        <tr><td height="20px"></td><td></td></tr>
        <tr>
            <td>Accent (US): </td>
            <td>
                <input type="text" name="accent_us" value="<?= $wordEdit->wordEditRequestModel->accent_us; ?>" placeholder="Accent as US" />
            </td>
        </tr>
        <tr><td height="20px"></td><td></td></tr>
        <tr>
            <td>Accent (UK): </td>
            <td>
                <input type="text" name="accent_uk" value="<?= $wordEdit->wordEditRequestModel->accent_uk; ?>" placeholder="Accent as UK" />
            </td>
        </tr>
        <tr><td height="20px"></td><td></td></tr>
        <tr>
            <td>Parts of Speech: </td>
            <td>
                <!--<input type="text" name="parts_of_spe" value="pronunciation" />-->
                <select name="parts_of_speech[]" multiple="multiple" required="required" size="7" style="height: 100%;">
                    <!--<option value="" selected="select">Select Any One</option>
                    <option value="n">Noun</option>
                    <option value="v">Verb</option>
                    <option value="vi">Verb Intens</option>
                    <option value="vt">Verb Trans</option>-->
                    <?= $wordEdit->getPartsOfSpeechOptions($wordEdit->wordEditRequestModel->parts_of_speech); ?>
                </select>
            </td>
        </tr>
        <tr><td height="20px"></td><td></td></tr>
        <tr>
            <td>Syllable: </td>
            <td>
                <input type="text" name="syllable" value="<?= $wordEdit->wordEditRequestModel->syllable; ?>" placeholder="Syllable" required="required" />
            </td>
        </tr>
        <tr><td height="20px"></td><td></td></tr>
        <tr>
            <td>Force Entry: </td>
            <td style="text-align: right; align-content: end;">
                <input type="hidden" name="force_entry" value="0" />
                <input type="checkbox" name="force_entry" value="1" />
            </td>
        </tr>
        <tr><td height="20px"></td><td></td></tr>
        <tr><td></td><td height="30px"></td></tr>
        <tr>
            <td></td>
            <td>
                <input type="hidden" name="word_edit_entry_form" value="word_edit_entry_form">
                <input type="hidden" name="word_language" value="<?= $wordLanguage; ?>">
                <input type="hidden" name="url_word_id" value="<?= $urlWordId; ?>">
                <input type="hidden" name="search_word" value="<?= $searchWord; ?>">
                <input type="hidden" name="url_word" value="<?= $urlWord; ?>">
            </td>
        </tr>
        <tr><td height="20px"></td><td></td></tr>
        <tr><td></td><td height="30px"></td></tr>
        <!--<tr>
            <td></td><td><input type="button" value="Submit" /></td>
        </tr>-->
        <tr>
            <td></td>
            <td class="form-button"><button class="button-6" type="submit">Submit</button></td>
        </tr>
    </table>
</form>