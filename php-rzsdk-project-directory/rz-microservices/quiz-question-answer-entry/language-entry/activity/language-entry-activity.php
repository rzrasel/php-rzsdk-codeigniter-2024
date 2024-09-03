<?php
namespace RzSDK\Quiz\Activity\Language\Entry;
?>
<?php
use RzSDK\Service\Listener\ServiceListener;
use RzSDK\Quiz\Model\HTTP\Request\Language\Parameter\RequestLanguageEntryQueryModel;
use RzSDK\Quiz\Service\Language\Entry\LanguageEntryActivityService;
use RzSDK\Utils\String\StringHelper;
use RzSDK\Log\DebugLog;
?>
<?php
class LanguageEntryActivity {
    //
    public ServiceListener $serviceListener;
    public RequestLanguageEntryQueryModel $languageEntryQueryModel;
    //

    public function __construct(ServiceListener $serviceListener) {
        $this->serviceListener = $serviceListener;
        $this->languageEntryQueryModel = new RequestLanguageEntryQueryModel();
    }

    public function execute($postedDataSet) {
        $queryParams = $this->languageEntryQueryModel->getQueryParams();
        //DebugLog::log($queryParams);
        if(empty($postedDataSet)) {
            foreach($queryParams as $key => $value) {
                $this->languageEntryQueryModel->$key = "";
            }
            return;
        }
        if(!array_key_exists($this->languageEntryQueryModel->getEntryFormName(), $postedDataSet)) {
            return;
        }
        //DebugLog::log($postedDataSet);
        foreach($queryParams as $key => $value) {
            //echo $key . ": " . $value . "\n";
            if(array_key_exists($value, $postedDataSet)) {
                $data = StringHelper::toSingleSpace($postedDataSet[$value]);
                $this->languageEntryQueryModel->$key = $data;
            }
        }
        //DebugLog::log($this->languageEntryQueryModel->getUrlParameters());
        //$this->serviceListener->onSuccess(null, "Success");
        $this->runServiceExecute($this->languageEntryQueryModel);
    }

    private function runServiceExecute(RequestLanguageEntryQueryModel $languageEntryQueryModel) {
        (new LanguageEntryActivityService(
            new class($this) implements ServiceListener {
                private LanguageEntryActivity $outerInstance;

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
                    $this->outerInstance->languageEntryQueryModel->language_name = null;
                    $this->outerInstance->serviceListener->onSuccess($dataSet, $message);
                }
            }
        ))
            ->execute($languageEntryQueryModel);
    }
}
?>
