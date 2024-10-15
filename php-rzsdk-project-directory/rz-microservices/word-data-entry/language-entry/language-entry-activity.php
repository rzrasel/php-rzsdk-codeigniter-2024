<?php
namespace RzSDK\Data\Entry\Activity;
?>
<?php
use RzSDK\Response\InfoType;
use RzSDK\HTTPResponse\LaunchResponse;
use RzSDK\Service\Listener\ServiceListener;
use RzSDK\Model\HTTP\Request\Language\HttpLanguageEntryRequestModel;
use RzSDK\Model\HTTP\Request\Language\HttpLanguageEntryResponseModel;
use RzSDK\Service\Entry\Activity\Language\LanguageEntryActivityService;
use RzSDK\Log\DebugLog;
?>
<?php
class LanguageEntryActivity {
    public ServiceListener $serviceListener;
    private $requestedDataSet;
    private HttpLanguageEntryResponseModel $languageEntryResponseModel;
    public function __construct(ServiceListener $serviceListener) {
        $this->serviceListener = $serviceListener;
        $this->languageEntryResponseModel = new HttpLanguageEntryResponseModel();
        $this->languageEntryResponseModel->data = null;
        $this->languageEntryResponseModel->is_error = true;
        $this->languageEntryResponseModel->message = "User data is empty";
    }

    public function execute($postedDataSet) {
        $languageEntryRequestModel = new HttpLanguageEntryRequestModel();
        //
        //DebugLog::log($postedDataSet);
        if(!empty($postedDataSet)) {
            $this->requestedDataSet = $postedDataSet;
            $languageEntryQuery = $languageEntryRequestModel->getQuery();
            foreach($languageEntryQuery as $key => $value) {
                if(key_exists($key, $postedDataSet)) {
                    //DebugLog::log($postedDataSet[$key]);
                    $postData = trim($postedDataSet[$key]);
                    if(!empty($postData)) {
                        $languageEntryRequestModel->$key = $postData;
                    } else {
                        $this->languageEntryResponseModel->message = "User required field is empty: {$value}";
                        $this->serviceListener->onError($this->languageEntryResponseModel, $this->languageEntryResponseModel->message);
                        return;
                    }
                } else {
                    $this->languageEntryResponseModel->message = "User required field not found: {$value}";
                    $this->serviceListener->onError($this->languageEntryResponseModel, $this->languageEntryResponseModel->message);
                    return;
                }
            }
        } else {
            $this->serviceListener->onError($this->languageEntryResponseModel, $this->languageEntryResponseModel->message);
            return;
        }
        //DebugLog::log($languageEntryRequestModel);
        $this->dataEntryStart($languageEntryRequestModel);
    }

    private function dataEntryStart(HttpLanguageEntryRequestModel $languageEntryRequestModel) {
        //DebugLog::log($languageEntryRequestModel);
        (new LanguageEntryActivityService(
            new class($this) implements ServiceListener {
                private LanguageEntryActivity $outerInstance;

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
            ->execute($languageEntryRequestModel);
    }

    /*public function setRequestPost($postedDataSet) {
        $languageEntryRequestModel = new HttpLanguageEntryRequestModel();
        //
        $languageEntryResponseModel = new HttpLanguageEntryResponseModel();
        $languageEntryResponseModel->language = null;
        $languageEntryResponseModel->is_error = true;
        $languageEntryResponseModel->message = "User data is empty";
        //
        if(!empty($postedDataSet)) {
            $this->requestedDataSet = $postedDataSet;
            $languageEntryQuery = $languageEntryRequestModel->getQuery();
            foreach($languageEntryQuery as $key => $value) {
                if(key_exists($key, $postedDataSet)) {
                    $languageEntryResponseModel->$key = $postedDataSet[$key];
                } else {
                    $languageEntryResponseModel->message = "User data required field is empty";
                    $this->serviceListener->onError($languageEntryResponseModel, $languageEntryResponseModel->message);
                }
            }
            DebugLog::log($languageEntryRequestModel->getQuery());
            DebugLog::log($this->requestedDataSet);
            //$this->response(array("redirect" => true, "page" => "registration"), "Error! Email or password not matched", InfoType::ERROR, $postedDataSet);
        } else {
            $this->serviceListener->onError($languageEntryResponseModel, $languageEntryResponseModel->message);
        }
    }*/

    private function response($body, $message, InfoType $infoType, $parameter = null) {
        $launchResponse = new LaunchResponse();
        $launchResponse->setBody($body)
            ->setInfo($message, $infoType)
            ->setParameter($parameter)
            ->execute();
    }
}
?>