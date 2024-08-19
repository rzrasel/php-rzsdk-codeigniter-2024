<?php
namespace RzSDK\Activity\Word\Meaning\Edit;
?>
<?php
use RzSDK\Service\Listener\ServiceListener;
use RzSDK\Shared\HTTP\Request\Parameter\RequestWordMeaningEditQueryModel;
use RzSDK\Service\Word\Meaning\Edit\Activity\Search\WordMeaningEditActivityService;
use RzSDK\Log\DebugLog;
?>
<?php
class WordMeaningEditActivity {
    //
    public ServiceListener $serviceListener;
    public RequestWordMeaningEditQueryModel $wordMeaningEditQueryModel;
    //
    public function __construct(ServiceListener $serviceListener) {
        $this->serviceListener = $serviceListener;
    }

    public function execute(RequestWordMeaningEditQueryModel $wordMeaningEditQueryModel) {
        $this->wordMeaningEditQueryModel = $wordMeaningEditQueryModel;
        if(empty($_POST)) {
            return;
        }
        if(!array_key_exists("word_meaning_edit_form", $_POST)) {
            return;
        }
        //
        $tempWordMeaningEditQueryModel = new RequestWordMeaningEditQueryModel();
        $postFieldList = array(
            $tempWordMeaningEditQueryModel->word,
            $tempWordMeaningEditQueryModel->meaning_language,
            $tempWordMeaningEditQueryModel->meaning,
        );
        $tempWordMeaningEditQueryModel = null;
        //
        foreach($postFieldList as $value) {
            $data = $this->wordMeaningEditQueryModel->$value;
            if(empty($data)) {
                $this->serviceListener->onError($this->wordMeaningEditQueryModel, "{$value} field is empty");
                return;
            }
        }
        //
        //$this->serviceListener->onError(null, $this->wordMeaningEditQueryModel);
        //DebugLog::log($this->wordMeaningEditQueryModel);
        $this->doExecuteService();
    }

    private function doExecuteService() {
        (new WordMeaningEditActivityService(
            new class($this) implements ServiceListener {
                private WordMeaningEditActivity $outerInstance;
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
        ))->execute($this->wordMeaningEditQueryModel);
    }
}
?>