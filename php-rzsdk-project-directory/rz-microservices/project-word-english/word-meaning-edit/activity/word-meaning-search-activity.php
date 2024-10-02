<?php
namespace RzSDK\Activity\Edit\Word\Meaning;
?>
<?php
use RzSDK\Service\Listener\ServiceListener;
use RzSDK\Utils\String\StringHelper;
use RzSDK\Model\HTTP\Request\Parameter\Word\Meaning\Edit\RequestWordMeaningEditQueryModel;
use RzSDK\Model\HTTP\Request\Parameter\Word\Meaning\Search\Edit\RequestWordMeaningSearchQueryModel;
use RzSDK\Log\DebugLog;
?>
<?php
class WordMeaningSearchActivity {
    //
    public ServiceListener $serviceListener;
    public RequestWordMeaningSearchQueryModel $wordMeaningSearchQueryModel;
    //
    public function __construct(ServiceListener $serviceListener) {
        $this->serviceListener = $serviceListener;
        $this->wordMeaningSearchQueryModel = new RequestWordMeaningSearchQueryModel();
    }

    public function execute($postedDataSet) {
        //DebugLog::log($postedDataSet);
        $queryParams = $this->wordMeaningSearchQueryModel->getQueryParams();
        if (empty($postedDataSet)) {
            foreach ($queryParams as $key => $value) {
                $this->wordMeaningSearchQueryModel->$key = "";
            }
            //$this->bookNameEntryQueryModel->book_name_is_default = false;
            return;
        }
        //
        if(!array_key_exists($this->wordMeaningSearchQueryModel->getEntryFormName(), $postedDataSet)) {
            return;
        }
        //DebugLog::log($postedDataSet);
        foreach($queryParams as $key => $value) {
            //echo $key . ": " . $value . "\n";
            if(array_key_exists($value, $postedDataSet)) {
                $data = StringHelper::toSingleSpace($postedDataSet[$value]);
                $this->wordMeaningSearchQueryModel->$key = $data;
            }
        }
        //
        //$this->runServiceExecute($this->wordMeaningSearchQueryModel);
    }

    private function runServiceExecute(RequestWordMeaningEditQueryModel $wordMeaningEditQueryModel) {
        /*(new WordMeaningEditActivityService(
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
                    $this->outerInstance->serviceListener->onSuccess($dataSet, $message);
                }
            }
        ))
            ->execute($wordMeaningEditQueryModel);*/
    }
}
?>