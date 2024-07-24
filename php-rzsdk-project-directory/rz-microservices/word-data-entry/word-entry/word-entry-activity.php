<?php
namespace RzSDK\Data\Entry\Activity;
?>
<?php
use RzSDK\Service\Listener\ServiceListener;
use RzSDK\Model\HTTP\Request\Word\HttpWordEntryRequestModel;
use RzSDK\Model\HTTP\Request\Word\HttpWordEntryResponseModel;
use RzSDK\Service\Entry\Activity\Language\WordEntryActivityService;
use RzSDK\Log\DebugLog;
?>
<?php
class WordEntryActivity {
    public ServiceListener $serviceListener;
    private $requestedDataSet;
    private HttpWordEntryResponseModel $wordEntryResponseModel;

    public function __construct(ServiceListener $serviceListener) {
        $this->serviceListener = $serviceListener;
        $this->wordEntryResponseModel = new HttpWordEntryResponseModel();
        $this->wordEntryResponseModel->data = null;
        $this->wordEntryResponseModel->is_error = true;
        $this->wordEntryResponseModel->message = "User data is empty";
    }

    public function execute($postedDataSet) {
        $wordEntryRequestModel = new HttpWordEntryRequestModel();
        //
        //DebugLog::log($postedDataSet);
        if(!empty($postedDataSet)) {
            $this->requestedDataSet = $postedDataSet;
            $wordEntryQuery = $wordEntryRequestModel->getQuery();
            //DebugLog::log($wordEntryQuery);
            foreach($wordEntryQuery as $key => $value) {
                if(array_key_exists($key, $postedDataSet)) {
                    //DebugLog::log($postedDataSet[$key]);
                    $postData = $postedDataSet[$key];
                    if(!is_array($postedDataSet[$key])) {
                        $postData = trim($postedDataSet[$key]);
                    }
                    if(is_numeric($postData)) {
                        $wordEntryRequestModel->$key = false;
                        if($postData == 1) {
                            $wordEntryRequestModel->$key = true;
                        }
                    }
                    if(!empty($postData)) {
                        $wordEntryRequestModel->$key = $postData;
                    } else {
                        $skipKeys = array(
                            "accent_us" => "accent_us",
                            "accent_uk" => "accent_uk",
                            "force_entry" => "force_entry",
                        );
                        if(!array_key_exists($key, $skipKeys)) {
                            //DebugLog::log($postData);
                            $this->wordEntryResponseModel->message = "User required field is empty: {$key}";
                            $this->serviceListener->onError($this->wordEntryResponseModel, $this->wordEntryResponseModel->message);
                            return;
                        } else {
                            $wordEntryRequestModel->$key = null;
                        }
                    }
                } else {
                    $this->wordEntryResponseModel->message = "User required field not found: {$value}";
                    $this->serviceListener->onError($this->wordEntryResponseModel, $this->wordEntryResponseModel->message);
                    return;
                }
            }
            if(!empty($wordEntryRequestModel->parts_of_speech)) {
                foreach ($wordEntryRequestModel->parts_of_speech as $key => $value) {
                    if(empty($value)) {
                        unset($wordEntryRequestModel->parts_of_speech[$key]);
                    }
                }
            }
            //DebugLog::log($wordEntryRequestModel);
            if(empty($wordEntryRequestModel->parts_of_speech)) {
                $this->wordEntryResponseModel->message = "User required field is empty: parts_of_speech";
                $this->serviceListener->onError($this->wordEntryResponseModel, $this->wordEntryResponseModel->message);
                return;
            }
        } else {
            $this->serviceListener->onError($this->wordEntryResponseModel, $this->wordEntryResponseModel->message);
            return;
        }
        //DebugLog::log($wordEntryRequestModel);
        $this->dataEntryStart($wordEntryRequestModel);
    }

    private function dataEntryStart(HttpWordEntryRequestModel $wordEntryRequestModel) {
        //DebugLog::log($wordEntryRequestModel);
        (new WordEntryActivityService(
            new class($this) implements ServiceListener {
                private WordEntryActivity $outerInstance;

                // Constructor to receive outer instance
                public function __construct($outerInstance) {
                    $this->outerInstance = $outerInstance;
                }

                public function onError($dataSet, $message) {
                    //DebugLog::log($dataSet);
                    $this->outerInstance->serviceListener->onError($dataSet, $message);
                }

                function onSuccess($dataSet, $message) {
                    //DebugLog::log($message);
                    $this->outerInstance->serviceListener->onSuccess($dataSet, $message);
                }
            }
        ))
            ->execute($wordEntryRequestModel);
    }
}
?>