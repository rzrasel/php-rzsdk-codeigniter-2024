<?php
namespace RzSDK\Quiz\Activity\Character\Token\Entry;
?>
<?php
use RzSDK\Service\Listener\ServiceListener;
use RzSDK\Quiz\Service\Character\Token\Entry\CharacterTableTokenEntryActivityService;
use RzSDK\Log\DebugLog;
?>
<?php
class CharacterTableTokenEntryActivity {
    //
    public ServiceListener $serviceListener;
    //public RequestCategoryTokenEntryQueryModel $categoryTokenEntryQueryModel;
    //

    public function __construct(ServiceListener $serviceListener) {
        $this->serviceListener = $serviceListener;
        //$this->categoryTokenEntryQueryModel = new RequestCategoryTokenEntryQueryModel();
    }

    public function execute($postedDataSet) {
        $this->runServiceExecute();
    }

    private function runServiceExecute() {
        (new CharacterTableTokenEntryActivityService(
            new class($this) implements ServiceListener {
                private CharacterTableTokenEntryActivity $outerInstance;

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
                    $this->outerInstance->serviceListener->onSuccess($dataSet, $message);
                }
            }
        ))
            ->execute();
    }
}
?>
