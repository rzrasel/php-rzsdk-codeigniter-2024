<?php
namespace RzSDK\Activity\Entry\Word\Meaning;
?>
<?php
use RzSDK\Service\Listener\ServiceListener;
use RzSDK\Model\HTTP\Request\Parameter\Word\Meaning\Entry\RequestWordMeaningEntryQueryModel;
use RzSDK\Service\Activity\Entry\Word\Meaning\WordMeaningEntryActivityService;
use RzSDK\Utils\String\StringHelper;
use RzSDK\Log\DebugLog;
?>
<?php
class WordMeaningEntryActivity {
    //
    public ServiceListener $serviceListener;
    public RequestWordMeaningEntryQueryModel $wordMeaningEntryQueryModel;
    //
    public function __construct(ServiceListener $serviceListener) {
        $this->serviceListener = $serviceListener;
        $this->wordMeaningEntryQueryModel = new RequestWordMeaningEntryQueryModel();
    }

    public function execute($postedDataSet) {
        //DebugLog::log($postedDataSet);
        $queryParams = $this->wordMeaningEntryQueryModel->getQueryParams();
        if (empty($postedDataSet)) {
            foreach ($queryParams as $key => $value) {
                $this->wordMeaningEntryQueryModel->$key = "";
            }
            //$this->bookNameEntryQueryModel->book_name_is_default = false;
            return;
        }
        //
        if(!array_key_exists($this->wordMeaningEntryQueryModel->getEntryFormName(), $postedDataSet)) {
            return;
        }
        //DebugLog::log($postedDataSet);
        foreach($queryParams as $key => $value) {
            //echo $key . ": " . $value . "\n";
            if(array_key_exists($value, $postedDataSet)) {
                $data = StringHelper::toSingleSpace($postedDataSet[$value]);
                $this->wordMeaningEntryQueryModel->$key = $data;
            }
        }
        //
        $this->runServiceExecute($this->wordMeaningEntryQueryModel);
    }

    private function runServiceExecute(RequestWordMeaningEntryQueryModel $wordMeaningEntryQueryModel) {
        (new WordMeaningEntryActivityService(
            new class($this) implements ServiceListener {
                private WordMeaningEntryActivity $outerInstance;

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
            ->execute($wordMeaningEntryQueryModel);
    }
}
?>