<?php
namespace RzSDK\Quiz\Activity\Category\Map\Entry;
?>
<?php
use RzSDK\Service\Listener\ServiceListener;
use RzSDK\Quiz\Model\HTTP\Request\Character\Map\Parameter\RequestCharacterMapEntryQueryModel;
use RzSDK\Utils\String\StringHelper;
use RzSDK\Quiz\Service\Character\Map\Entry\CharacterTableMapEntryActivityService;
use RzSDK\Log\DebugLog;
?>
<?php
class CharacterTableMapEntryActivity {
    //
    public ServiceListener $serviceListener;
    public RequestCharacterMapEntryQueryModel $characterMapEntryQueryModel;
    //
    public function __construct(ServiceListener $serviceListener) {
        $this->serviceListener = $serviceListener;
        $this->characterMapEntryQueryModel = new RequestCharacterMapEntryQueryModel();
    }

    public function execute($postedDataSet) {
        $queryParams = $this->characterMapEntryQueryModel->getQueryParams();
        //DebugLog::log($queryParams);
        if (empty($postedDataSet)) {
            foreach ($queryParams as $key => $value) {
                $this->characterMapEntryQueryModel->$key = "";
            }
            return;
        }
        if(!array_key_exists($this->characterMapEntryQueryModel->getEntryFormName(), $postedDataSet)) {
            return;
        }

        foreach($queryParams as $key => $value) {
            //echo $key . ": " . $value . "\n";
            if(array_key_exists($value, $postedDataSet)) {
                $data = StringHelper::toSingleSpace($postedDataSet[$value]);
                $this->characterMapEntryQueryModel->$key = $data;
            } else {
                $this->serviceListener->onError(null, "All fields are required.");
                return;
            }
        }
        //DebugLog::log($this->characterMapEntryQueryModel);
        //DebugLog::log($this->characterMapEntryQueryModel->getUrlParameters());
        $this->runServiceExecute($this->characterMapEntryQueryModel);
    }

    private function runServiceExecute(RequestCharacterMapEntryQueryModel $characterMapEntryQueryModel) {
        //DebugLog::log($characterMapEntryQueryModel);
        (new CharacterTableMapEntryActivityService(
            new class($this) implements ServiceListener {
                private CharacterTableMapEntryActivity $outerInstance;

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
                    //$this->outerInstance->characterMapEntryQueryModel->language_id = "";
                    $this->outerInstance->characterMapEntryQueryModel->character_name = "";
                    $this->outerInstance->serviceListener->onSuccess($dataSet, $message);
                }
            }
        ))
            ->execute($characterMapEntryQueryModel);
    }
}
?>
