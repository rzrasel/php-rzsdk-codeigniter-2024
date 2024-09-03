<?php
namespace RzSDK\Quiz\Activity\Book\Token\Entry;
?>
<?php
use RzSDK\Service\Listener\ServiceListener;
use RzSDK\Quiz\Model\HTTP\Request\Book\Token\Parameter\RequestBookTokenEntryQueryModel;
use RzSDK\Quiz\Service\Book\Token\Entry\BookTokenEntryActivityService;
use RzSDK\Utils\String\StringHelper;
use RzSDK\Log\DebugLog;
?>
<?php
class BookTokenEntryActivity {
    //
    public ServiceListener $serviceListener;
    public RequestBookTokenEntryQueryModel $bookTokenEntryQueryModel;

    //

    public function __construct(ServiceListener $serviceListener) {
        $this->serviceListener = $serviceListener;
        $this->bookTokenEntryQueryModel = new RequestBookTokenEntryQueryModel();
    }

    public function execute($postedDataSet) {
        $queryParams = $this->bookTokenEntryQueryModel->getQueryParams();
        //DebugLog::log($queryParams);
        if(empty($postedDataSet)) {
            foreach($queryParams as $key => $value) {
                $this->bookTokenEntryQueryModel->$key = "";
            }
            return;
        }
        if(!array_key_exists($this->bookTokenEntryQueryModel->getEntryFormName(), $postedDataSet)) {
            return;
        }
        //DebugLog::log($postedDataSet);
        foreach($queryParams as $key => $value) {
            //echo $key . ": " . $value . "\n";
            if(array_key_exists($value, $postedDataSet)) {
                $data = StringHelper::toSingleSpace($postedDataSet[$value]);
                $this->bookTokenEntryQueryModel->$key = $data;
            }
        }
        //DebugLog::log($this->bookTokenEntryQueryModel->getUrlParameters());
        $this->runServiceExecute($this->bookTokenEntryQueryModel);
    }

    private function runServiceExecute(RequestBookTokenEntryQueryModel $bookTokenEntryQueryModel) {
        (new BookTokenEntryActivityService(
            new class($this) implements ServiceListener {
                private BookTokenEntryActivity $outerInstance;

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
                    $this->outerInstance->bookTokenEntryQueryModel->book_token_name = null;
                    $this->outerInstance->serviceListener->onSuccess($dataSet, $message);
                }
            }
        ))
            ->execute($bookTokenEntryQueryModel);
    }
}
?>