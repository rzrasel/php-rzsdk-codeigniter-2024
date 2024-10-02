<?php
namespace RzSDK\Activity\Search\Word\Meaning;
?>
<?php
use RzSDK\Service\Listener\ServiceListener;
use RzSDK\Model\HTTP\Request\Parameter\Word\Meaning\NewLine\RequestWordMeaningNewLineQueryModel;
use RzSDK\Service\Activity\Translate\Word\Meaning\WordMeaningNewLineActivityService;
use RzSDK\Utils\String\StringHelper;
use RzSDK\Log\DebugLog;
?>
<?php
class WordMeaningNewLineActivity {
    //
    public ServiceListener $serviceListener;
    public RequestWordMeaningNewLineQueryModel $wordMeaningNewLineQueryModel;
    //
    public function __construct(ServiceListener $serviceListener) {
        $this->serviceListener = $serviceListener;
        $this->wordMeaningNewLineQueryModel = new RequestWordMeaningNewLineQueryModel();
    }

    public function execute($postedDataSet) {
        //DebugLog::log($postedDataSet);
        $queryParams = $this->wordMeaningNewLineQueryModel->getQueryParams();
        if (empty($postedDataSet)) {
            foreach ($queryParams as $key => $value) {
                $this->wordMeaningNewLineQueryModel->$key = "";
            }
            //$this->bookNameEntryQueryModel->book_name_is_default = false;
            return;
        }
        //
        if(!array_key_exists($this->wordMeaningNewLineQueryModel->getEntryFormName(), $postedDataSet)) {
            return;
        }
        //DebugLog::log($postedDataSet);
        foreach($queryParams as $key => $value) {
            //echo $key . ": " . $value . "\n";
            if(array_key_exists($value, $postedDataSet)) {
                $data = StringHelper::toSingleSpace($postedDataSet[$value]);
                $this->wordMeaningNewLineQueryModel->$key = $data;
            }
        }
        //
        $this->runServiceExecute($this->wordMeaningNewLineQueryModel);
    }

    private function runServiceExecute(RequestWordMeaningNewLineQueryModel $wordMeaningNewLineQueryModel) {
        (new WordMeaningNewLineActivityService(
            new class($this) implements ServiceListener {
                private WordMeaningNewLineActivity $outerInstance;

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
            ->execute($wordMeaningNewLineQueryModel);
    }
}
?>