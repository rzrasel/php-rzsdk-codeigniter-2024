<?php
namespace RzSDK\Quiz\Activity\Book\Name\Entry;
?>
<?php
use RzSDK\Service\Listener\ServiceListener;
use RzSDK\Quiz\Model\HTTP\Request\Book\Token\Parameter\RequestBookTokenEntryQueryModel;
use RzSDK\Quiz\Model\HTTP\Request\Book\Name\Parameter\RequestBookNameEntryQueryModel;
use RzSDK\Quiz\Service\Book\Token\Entry\BookNameEntryActivityService;
use RzSDK\Universal\Language\PullLanguageList;
use RzSDK\Universal\Language\Select\Option\BuildLanguageSelectOptions;
use RzSDK\Log\DebugLog;
?>
<?php
class BookNameEntryActivity {
    //
    public ServiceListener $serviceListener;
    public RequestBookNameEntryQueryModel $bookNameEntryQueryModel;

    //

    public function __construct(ServiceListener $serviceListener) {
        $this->serviceListener = $serviceListener;
        $this->bookNameEntryQueryModel = new RequestBookNameEntryQueryModel();
    }

    public function execute($postedDataSet) {
        $queryParams = $this->bookNameEntryQueryModel->getQueryParams();
        //DebugLog::log($queryParams);
        if (empty($postedDataSet)) {
            foreach ($queryParams as $key => $value) {
                $this->bookNameEntryQueryModel->$key = "";
            }
            //$this->bookNameEntryQueryModel->book_name_is_default = false;
            return;
        }
        if(!array_key_exists($this->bookNameEntryQueryModel->getEntryFormName(), $postedDataSet)) {
            return;
        }
        //DebugLog::log($postedDataSet);
        foreach($queryParams as $key => $value) {
            //echo $key . ": " . $value . "\n";
            if(array_key_exists($value, $postedDataSet)) {
                $this->bookNameEntryQueryModel->$key = $postedDataSet[$value];
            }
        }
        //DebugLog::log($this->bookNameEntryQueryModel);
        //DebugLog::log($this->bookNameEntryQueryModel->getUrlParameters());
        $this->runServiceExecute($this->bookNameEntryQueryModel);
    }

    private function runServiceExecute(RequestBookNameEntryQueryModel $bookNameEntryQueryModel) {
        (new BookNameEntryActivityService(
            new class($this) implements ServiceListener {
                private BookNameEntryActivity $outerInstance;

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
                    $this->outerInstance->bookNameEntryQueryModel->book_name = "";
                    $this->outerInstance->bookNameEntryQueryModel->book_name_is_default = false;
                    $this->outerInstance->serviceListener->onSuccess($dataSet, $message);
                }
            }
        ))
            ->execute($bookNameEntryQueryModel);
    }
}
?>
