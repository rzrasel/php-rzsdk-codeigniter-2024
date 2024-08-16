<?php
namespace RzSDK\Module\HTTP\Request\Data\Word\Edit;
?>
<?php
require_once("../include.php");
?>
<?php
use RzSDK\Service\Listener\ServiceListener;
use RzSDK\Model\HTTP\Request\Word\Edit\HttpWordEditRequestModel;
use RzSDK\Service\Entry\Activity\Word\WordEditService;
use RzSDK\Word\Edit\Search\Word\Activity\WordSearchActivity;
use RzSDK\Service\Word\Edit\Database\Preposition\WordEditPreDatabaseService;
use RzSDK\Log\DebugLog;
?>
<?php
class WordEditRequestedDataModule {
    //
    public ServiceListener $serviceListener;
    public HttpWordEditRequestModel $wordEditRequestModel;
    //
    public function __construct(ServiceListener $serviceListener) {
        $this->serviceListener = $serviceListener;
        $this->wordEditRequestModel = new HttpWordEditRequestModel();
    }

    public function execute() {
        //DebugLog::log($_POST);
        //
        //DebugLog::log($_REQUEST);
        $entryRequestQuery = $this->wordEditRequestModel->getQuery();
        if(!empty($_REQUEST)) {
            //DebugLog::log($_REQUEST);
            //DebugLog::log($entryRequestQuery);
            foreach($entryRequestQuery as $key => $value) {
                if(array_key_exists($key, $_REQUEST)) {
                    $this->wordEditRequestModel->$key = $_REQUEST[$key];
                } else {
                    $this->wordEditRequestModel->$key = "";
                }
            }
            //$this->wordEditRequestModel->search_word = $this->wordEditRequestModel->url_word;
            $this->wordEditRequestModel->word = $this->wordEditRequestModel->url_word;
        }
        //DebugLog::log($this->wordEditRequestModel);
        //
        $entryRequestQuery = $this->wordEditRequestModel->getQuery();
        foreach($entryRequestQuery as $key => $value) {
            if(array_key_exists($key, $_POST)) {
                $this->wordEditRequestModel->$key = $_POST[$key];
            } else {
                //$this->wordEditRequestModel->$key = null;
            }
        }
        //
        $this->executePreDatabase($this->wordEditRequestModel);
        //
        if(empty($_POST)) {
            return;
        }
        if(!array_key_exists("word_edit_entry_form", $_POST)) {
            return;
        }
        //DebugLog::log($this->wordEditRequestModel);
        //$this->executePreDatabase($this->wordEditRequestModel);
    }

    public function executePreDatabase(HttpWordEditRequestModel $wordEditRequestModel) {
        //DebugLog::log($this->wordEditRequestModel);
        $wordEditRequestModel->pronunciation = "pronunciation";
        $wordEditRequestModel->accent_us = "accent_us";
        $wordEditRequestModel->accent_uk = "accent_uk";
        $wordEditRequestModel->syllable = "syllable";
        //
        (new WordEditPreDatabaseService(
            new class($this) implements ServiceListener {
                private WordEditRequestedDataModule $outerInstance;

                // Constructor to receive outer instance
                public function __construct($outerInstance) {
                    $this->outerInstance = $outerInstance;
                }
                public function onError($dataSet, $message) {
                    DebugLog::log($dataSet);
                    DebugLog::log($message);
                }
                function onSuccess($dataSet, $message) {
                    DebugLog::log($dataSet);
                    DebugLog::log($message);
                }
            }
        ))->execute($wordEditRequestModel);
    }

    public function getLanguageOptions($languageId = "", $defaultLanguage = "English") {
        $wordEntryService = new WordEditService();
        return $wordEntryService->getLanguageOptions($languageId, $defaultLanguage);
    }

    public function getPartsOfSpeechOptions($optionList) {
        $wordEntryService = new WordEditService();
        return $wordEntryService->getPartsOfSpeechOptions($optionList);
    }
}
?>
