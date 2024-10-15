<?php
namespace RzSDK\Module\Word\Meaning\Edit;
?>
<?php
use RzSDK\Service\Listener\ServiceListener;
use RzSDK\Shared\HTTP\Request\Parameter\RequestWordMeaningEditQueryModel;
use RzSDK\Log\DebugLog;
?>
<?php
class WordMeaningEditModule {
    //
    public ServiceListener $serviceListener;
    public RequestWordMeaningEditQueryModel $wordMeaningEditQueryModel;
    //
    public function __construct(ServiceListener $serviceListener) {
        $this->serviceListener = $serviceListener;
        $this->wordMeaningEditQueryModel = new RequestWordMeaningEditQueryModel();
    }

    public function execute() {
        $this->setRequestGetValue();
        $this->setRequestPostValue();
    }

    private function setRequestGetValue() {
        $wordMeaningEditQuery = $this->wordMeaningEditQueryModel->getWordMeaningEditQuery();
        if(empty($_GET)) {
            foreach($wordMeaningEditQuery as $value) {
                $this->wordMeaningEditQueryModel->$value = null;
            }
            $tempWordMeaningEditQueryModel = new RequestWordMeaningEditQueryModel();
            $tempWordMeaningEditForm = $tempWordMeaningEditQueryModel->word_meaning_edit_form;
            $tempWordMeaningEditQueryModel = null;
            $this->wordMeaningEditQueryModel->$tempWordMeaningEditForm = $tempWordMeaningEditForm;
            return;
        }
        foreach($wordMeaningEditQuery as $value) {
            if(array_key_exists($value, $_GET)) {
                $this->wordMeaningEditQueryModel->$value = $_GET[$value];
            } else {
                $this->wordMeaningEditQueryModel->$value = null;
            }
        }
        // Word Meaning Edit Form Field
        $tempWordMeaningEditQueryModel = new RequestWordMeaningEditQueryModel();
        $tempWordMeaningEditForm = $tempWordMeaningEditQueryModel->word_meaning_edit_form;
        $tempWordMeaningEditQueryModel = null;
        $this->wordMeaningEditQueryModel->$tempWordMeaningEditForm = $tempWordMeaningEditForm;
        //
        $this->wordMeaningEditQueryModel->meaning_language = $this->wordMeaningEditQueryModel->url_word_meaning_language;
        $this->wordMeaningEditQueryModel->word = $this->wordMeaningEditQueryModel->url_word;
        $this->wordMeaningEditQueryModel->meaning_id = $this->wordMeaningEditQueryModel->url_meaning_id;
        $this->wordMeaningEditQueryModel->meaning = $this->wordMeaningEditQueryModel->url_meaning;
    }

    private function setRequestPostValue() {
        if(empty($_POST)) {
            return;
        }
        /*if(empty($_POST["word_meaning_edit_form"])) {
            $this->serviceListener->onError(null, "word_meaning_edit_form value empty");
            return;
        }*/
        //
        $wordMeaningEditQuery = $this->wordMeaningEditQueryModel->getWordMeaningEditQuery();
        foreach($wordMeaningEditQuery as $value) {
            if(array_key_exists($value, $_POST)) {
                $this->wordMeaningEditQueryModel->$value = $_POST[$value];
            }
        }
    }
}
?>