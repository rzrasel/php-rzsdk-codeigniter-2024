<?php
namespace RzSDK\Data\Entry\Meaning\Activity;
?>
<?php
use RzSDK\Service\Listener\ServiceListener;
use RzSDK\Model\HTTP\Request\Word\Meaning\Entry\Model\HttpWordMeaningEntryRequestModel;
use RzSDK\Model\HTTP\Request\Word\Meaning\Entry\Model\HttpWordMeaningEntryResponseModel;
use RzSDK\Service\Entry\Meaning\Activity\WordMeaningEntryActivityService;
use RzSDK\Log\DebugLog;
?>
<?php
class WordMeaningEntryActivity {
    public ServiceListener $serviceListener;
    private HttpWordMeaningEntryResponseModel $meaningEntryResponseModel;
    //
    public function __construct(ServiceListener $serviceListener) {
        $this->serviceListener = $serviceListener;
        $this->meaningEntryResponseModel = new HttpWordMeaningEntryResponseModel();
        $this->meaningEntryResponseModel->data = null;
        $this->meaningEntryResponseModel->is_error = true;
        $this->meaningEntryResponseModel->message = "Unknown error";
    }

    public function execute($postedDataSet) {
        //DebugLog::log($postedDataSet);
        if(empty($postedDataSet)) {
            $this->meaningEntryResponseModel->data = null;
            $this->meaningEntryResponseModel->is_error = true;
            $this->meaningEntryResponseModel->message = "Error! data empty";
            $this->serviceListener->onError($this->meaningEntryResponseModel, $this->meaningEntryResponseModel->message);
            return;
        }
        //DebugLog::log($postedDataSet);
        $meaningEntryRequestModel = new HttpWordMeaningEntryRequestModel();
        $entryRequestQuery = $meaningEntryRequestModel->getQuery();
        foreach($entryRequestQuery as $key => $value) {
            if(array_key_exists($key, $_POST)) {
                $requestValue = trim($_POST[$key]);
                $meaningEntryRequestModel->$key = $requestValue;
                if(empty($requestValue) && $key != "search_word") {
                    $this->meaningEntryResponseModel->data = null;
                    $this->meaningEntryResponseModel->is_error = true;
                    $this->meaningEntryResponseModel->message = "Error! data field empty: {$key}";
                    $this->serviceListener->onError($this->meaningEntryResponseModel, $this->meaningEntryResponseModel->message);
                    return;
                }
            } else {
                $meaningEntryRequestModel->$key = null;
                $this->meaningEntryResponseModel->data = null;
                $this->meaningEntryResponseModel->is_error = true;
                $this->meaningEntryResponseModel->message = "Error! data field not found: {$key}";
                $this->serviceListener->onError($this->meaningEntryResponseModel, $this->meaningEntryResponseModel->message);
                return;
            }
        }
        //DebugLog::log($meaningEntryRequestModel);
        $this->dataEntryStart($meaningEntryRequestModel);
    }

    private function dataEntryStart(HttpWordMeaningEntryRequestModel $meaningEntryRequestModel) {
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
                    $this->outerInstance->serviceListener->onSuccess($dataSet, $message);
                }
            }
        ))->execute($meaningEntryRequestModel);
    }
}
?>