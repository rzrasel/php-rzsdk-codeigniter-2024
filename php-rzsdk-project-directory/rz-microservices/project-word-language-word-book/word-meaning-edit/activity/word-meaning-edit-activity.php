<?php
namespace RzSDK\Activity\Edit\Word\Meaning;
?>
<?php
use RzSDK\Service\Listener\ServiceListener;
use RzSDK\Utils\String\StringHelper;
use RzSDK\Service\Activity\Edit\Word\Meaning\WordMeaningEditActivityService;
use RzSDK\Model\HTTP\Request\Parameter\Word\Meaning\Edit\RequestWordMeaningEditQueryModel;
use RzSDK\Log\DebugLog;
?>
<?php
class WordMeaningEditActivity {
    //
    public ServiceListener $serviceListener;
    public RequestWordMeaningEditQueryModel $wordMeaningEditQueryModel;
    //
    public function __construct(ServiceListener $serviceListener) {
        $this->serviceListener = $serviceListener;
        $this->wordMeaningEditQueryModel = new RequestWordMeaningEditQueryModel();
    }

    public function execute($postedDataSet) {
        //DebugLog::log($postedDataSet);
        $queryParams = $this->wordMeaningEditQueryModel->getQueryParams();
        $searchWordIdParam = $this->wordMeaningEditQueryModel->word_id;
        if(empty($_REQUEST)) {
            foreach ($queryParams as $key => $value) {
                $this->wordMeaningEditQueryModel->$key = "";
            }
            return;
        }
        if(empty($_REQUEST[$searchWordIdParam])) {
            foreach ($queryParams as $key => $value) {
                $this->wordMeaningEditQueryModel->$key = "";
            }
            return;
        }
        //
        foreach($queryParams as $key => $value) {
            //echo $key . ": " . $value . "\n";
            if(array_key_exists($value, $_REQUEST)) {
                $data = StringHelper::toSingleSpace($_REQUEST[$value]);
                $this->wordMeaningEditQueryModel->$key = $data;
            }
        }
        //
        if(!array_key_exists($this->wordMeaningEditQueryModel->getEntryFormName(), $postedDataSet)) {
            return;
        }
        //DebugLog::log($postedDataSet);
        foreach($queryParams as $key => $value) {
            //echo $key . ": " . $value . "\n";
            if(array_key_exists($value, $postedDataSet)) {
                $data = StringHelper::toSingleSpace($postedDataSet[$value]);
                $this->wordMeaningEditQueryModel->$key = $data;
            }
        }
        //
        $this->runServiceExecute($this->wordMeaningEditQueryModel);
    }

    private function runServiceExecute(RequestWordMeaningEditQueryModel $wordMeaningEditQueryModel) {
        (new WordMeaningEditActivityService(
            new class($this) implements ServiceListener {
                private WordMeaningEditActivity $outerInstance;

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
            ->execute($wordMeaningEditQueryModel);
    }
}
?>