<?php
namespace RzSDK\Activity\Search\Word\Meaning;
?>
<?php
use RzSDK\Service\Listener\ServiceListener;
use RzSDK\Model\HTTP\Request\Parameter\Word\Meaning\Pronunciation\RequestWordMeaningPronunciationQueryModel;
use RzSDK\Service\Activity\Translate\Word\Meaning\WordMeaningPronunciationActivityService;
use RzSDK\Utils\String\StringHelper;
use RzSDK\Log\DebugLog;
?>
<?php
class WordMeaningPronunciationActivity {
    //
    public ServiceListener $serviceListener;
    public RequestWordMeaningPronunciationQueryModel $wordMeaningPronunciationQueryModel;
    //
    public function __construct(ServiceListener $serviceListener) {
        $this->serviceListener = $serviceListener;
        $this->wordMeaningPronunciationQueryModel = new RequestWordMeaningPronunciationQueryModel();
    }

    public function execute($postedDataSet) {
        //DebugLog::log($postedDataSet);
        $queryParams = $this->wordMeaningPronunciationQueryModel->getQueryParams();
        if (empty($postedDataSet)) {
            foreach ($queryParams as $key => $value) {
                $this->wordMeaningPronunciationQueryModel->$key = "";
            }
            //$this->bookNameEntryQueryModel->book_name_is_default = false;
            return;
        }
        //
        if(!array_key_exists($this->wordMeaningPronunciationQueryModel->getEntryFormName(), $postedDataSet)) {
            return;
        }
        //DebugLog::log($postedDataSet);
        foreach($queryParams as $key => $value) {
            //echo $key . ": " . $value . "\n";
            if(array_key_exists($value, $postedDataSet)) {
                $data = StringHelper::toSingleSpace($postedDataSet[$value]);
                $this->wordMeaningPronunciationQueryModel->$key = $data;
            }
        }
        //
        $this->runServiceExecute($this->wordMeaningPronunciationQueryModel);
    }

    private function runServiceExecute(RequestWordMeaningPronunciationQueryModel $wordMeaningPronunciationQueryModel) {
        (new WordMeaningPronunciationActivityService(
            new class($this) implements ServiceListener {
                private WordMeaningPronunciationActivity $outerInstance;

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
                    //$this->outerInstance->bookNameEntryQueryModel->language_id = "";
                    //$this->outerInstance->bookNameEntryQueryModel->book_token_id = "";
                    /*$this->outerInstance->bookNameEntryQueryModel->book_name = "";
                    $this->outerInstance->bookNameEntryQueryModel->book_name_slug = "";
                    $this->outerInstance->bookNameEntryQueryModel->book_name_is_default = false;*/
                    $this->outerInstance->serviceListener->onSuccess($dataSet, $message);
                }
            }
        ))
            ->execute($wordMeaningPronunciationQueryModel);
    }
}
?>