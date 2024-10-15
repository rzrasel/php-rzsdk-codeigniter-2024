<?php
namespace RzSDK\Word\Entry;
?>
<?php
require_once("include.php");
?>
<?php
use RzSDK\Utils\Alert\Message\AlertMessageBox;
use RzSDK\URL\SiteUrl;
use RzSDK\Word\Navigation\SideLink;
use RzSDK\Service\Listener\ServiceListener;
use RzSDK\Data\Entry\Activity\WordEntryActivity;
use RzSDK\Model\HTTP\Request\Word\HttpWordEntryRequestModel;
use RzSDK\Service\Entry\Activity\Language\WordEntryService;
use RzSDK\Log\DebugLog;
?>
<?php
class WordEntry {
    public ServiceListener $serviceListener;
    public HttpWordEntryRequestModel $entryRequestModel;
    public function __construct(ServiceListener $serviceListener) {
        $this->serviceListener = $serviceListener;
        $this->entryRequestModel = new HttpWordEntryRequestModel();
        /*$entryRequestQuery = $this->entryRequestModel->getQuery();
        //DebugLog::log($entryRequestQuery);
        foreach($entryRequestQuery as $item) {
            $this->entryRequestModel->$item = "";
        }*/
    }

    public function execute() {
        //$this->entryRequestModel = new HttpWordEntryRequestModel();
        /*if(empty($_POST)) {
            return;
        }*/
        $entryRequestQuery = $this->entryRequestModel->getQuery();
        foreach($entryRequestQuery as $key => $value) {
            if(array_key_exists($key, $_POST)) {
                $this->entryRequestModel->$key = $_POST[$key];
            } else {
                $this->entryRequestModel->$key = null;
            }
        }
        if(empty($_POST)) {
            return;
        }
        if(!empty($this->entryRequestModel->parts_of_speech)) {
            foreach ($this->entryRequestModel->parts_of_speech as $key => $value) {
                if(empty($value)) {
                    unset($this->entryRequestModel->parts_of_speech[$key]);
                }
            }
        }
        /*if(!empty($value)) {
            $tempPartsOfSpeech[] = $value;
        }*/
        //DebugLog::log($_POST);
        //DebugLog::log($this->entryRequestModel);
        (new WordEntryActivity(
            new class($this) implements ServiceListener {
                private WordEntry $outerInstance;

                // Constructor to receive outer instance
                public function __construct($outerInstance) {
                    $this->outerInstance = $outerInstance;
                }

                public function onError($dataSet, $message) {
                    //DebugLog::log($dataSet);
                    //DebugLog::log($message);
                    $this->outerInstance->serviceListener->onError($dataSet, $message);
                }

                function onSuccess($dataSet, $message) {
                    //DebugLog::log($dataSet);
                    //DebugLog::log($message);
                    $this->outerInstance->entryRequestModel->pronunciation = null;
                    $this->outerInstance->entryRequestModel->accent_us = null;
                    $this->outerInstance->entryRequestModel->accent_uk = null;
                    $this->outerInstance->entryRequestModel->parts_of_speech = null;
                    $this->outerInstance->entryRequestModel->syllable = null;
                    $this->outerInstance->serviceListener->onSuccess($dataSet, $message);
                }
            }
        ))
            ->execute($this->entryRequestModel->getQuery());
    }

    public function getLanguageOptions($languageId = "", $defaultLanguage = "English") {
        $wordEntryService = new WordEntryService();
        return $wordEntryService->getLanguageOptions($languageId, $defaultLanguage);
    }
    public function getPartsOfSpeechOptions() {
        $wordEntryService = new WordEntryService();
        return $wordEntryService->getPartsOfSpeechOptions($this->entryRequestModel);
    }
}
//(new WordEntry())->execute();
/*$wordEntry = new WordEntry();
$wordEntry->execute();*/
//DebugLog::log($wordEntry->getPartsOptions());
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
    <title>Word Entry</title>
    <link href="<?= $projectBaseUrl; ?>/css/style.css"  rel="stylesheet" type="text/css" charset="utf-8">
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
                                <td class="table-entry-form-holder-page-header">Word Entry Form</td>
                            </tr>
                            <tr>
                                <td class="response-message-section">
                                    <div class="response-message-box">
<?php
$wordEntry = new WordEntry(
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
$wordEntry->execute();
?>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <form action="<?= $_SERVER["PHP_SELF"]; ?>" method="POST">
                                        <table class="table-entry-form-field-container">
                                            <tr>
                                                <td class="table-entry-form-field-left">Language: </td>
                                                <td class="table-entry-form-field-right">
                                                    <select name="language" required="required">
                                                        <!--<option value="172157799761295096">Bangla</option>
                                                        <option value="172157831436333409" selected="select">English</option>-->
                                                        <?= $wordEntry->getLanguageOptions(); ?>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr><td height="20px"></td><td></td></tr>
                                            <tr>
                                                <td>Word: </td>
                                                <td>
                                                    <input type="text" name="word" value="<?= $wordEntry->entryRequestModel->word; ?>" placeholder="Dictionary Word" required="required" />
                                                </td>
                                            </tr>
                                            <tr><td height="20px"></td><td></td></tr>
                                            <tr>
                                                <td>Pronunciation: </td>
                                                <td>
                                                    <input type="text" name="pronunciation" value="<?= $wordEntry->entryRequestModel->pronunciation; ?>" placeholder="Word Pronunciation" required="required" />
                                                </td>
                                            </tr>
                                            <tr><td height="20px"></td><td></td></tr>
                                            <tr>
                                                <td>Accent (US): </td>
                                                <td>
                                                    <input type="text" name="accent_us" value="<?= $wordEntry->entryRequestModel->accent_us; ?>" placeholder="Accent as US" />
                                                </td>
                                            </tr>
                                            <tr><td height="20px"></td><td></td></tr>
                                            <tr>
                                                <td>Accent (UK): </td>
                                                <td>
                                                    <input type="text" name="accent_uk" value="<?= $wordEntry->entryRequestModel->accent_uk; ?>" placeholder="Accent as UK" />
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
                                                        <?= $wordEntry->getPartsOfSpeechOptions(); ?>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr><td height="20px"></td><td></td></tr>
                                            <tr>
                                                <td>Syllable: </td>
                                                <td>
                                                    <input type="text" name="syllable" value="<?= $wordEntry->entryRequestModel->syllable; ?>" placeholder="Syllable" required="required" />
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
                                            <!--<tr>
                                                <td></td><td><input type="button" value="Submit" /></td>
                                            </tr>-->
                                            <tr>
                                                <td></td>
                                                <td class="form-button"><button class="button-6" type="submit">Submit</button></td>
                                            </tr>
                                        </table>
                                    </form>
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
