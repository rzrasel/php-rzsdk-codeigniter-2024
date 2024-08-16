<?php
namespace RzSDK\Word\Edit\Activity;
?>
<?php
use RzSDK\Service\Listener\ServiceListener;
use RzSDK\Model\HTTP\Request\Word\Edit\HttpWordEditRequestModel;
use RzSDK\Service\Activity\Word\Edit\WordEditActivityService;
use RzSDK\Log\DebugLog;
?>
<?php
class WordEditActivity {
    //
    public ServiceListener $serviceListener;
    //
    public function __construct(ServiceListener $serviceListener) {
        $this->serviceListener = $serviceListener;
    }

    public function execute(HttpWordEditRequestModel $wordEntryRequestModel) {
        //DebugLog::log($wordEntryRequestModel);
        if(empty($_POST)) {
            //$this->serviceListener->onError(null, "Post value empty");
            return;
        }
        //DebugLog::log($_POST);
        if(empty($_POST["word_edit_entry_form"])) {
            $this->serviceListener->onError(null, "word_edit_entry_form value empty");
            return;
        }
        $entryRequestQuery = $wordEntryRequestModel->getQuery();
        foreach($entryRequestQuery as $key => $value) {
            if(array_key_exists($key, $_POST)) {
                $wordEntryRequestModel->$key = $_POST[$key];
                if($key == "parts_of_speech") {
                    $wordEntryRequestModel->parts_of_speech = $_POST[$key];
                }
            }
        }
        //DebugLog::log($wordEntryRequestModel);
        //
        (new WordEditActivityService(
            new class($this) implements ServiceListener {
                private WordEditActivity $outerInstance;
                public function __construct($outerInstance) {
                    $this->outerInstance = $outerInstance;
                }

                public function onError($dataSet, $message) {
                    //DebugLog::log($dataSet);
                    //DebugLog::log($message);
                    //$this->outerInstance->responseData = $message;
                    $this->outerInstance->serviceListener->onError($dataSet, $message);
                }

                function onSuccess($dataSet, $message) {
                    //DebugLog::log($dataSet);
                    //DebugLog::log($message);
                    //$this->outerInstance->responseData = $dataSet->data;
                    $this->outerInstance->serviceListener->onSuccess($dataSet, $message);
                    //DebugLog::log($this->outerInstance->responseData);
                }
            }
        ))->execute($wordEntryRequestModel);
    }
}
?>