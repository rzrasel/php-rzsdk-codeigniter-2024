<?php
namespace RzSDK\Dictionary\Search\Module;
?>
<?php
require_once("include.php");
?>
<?php
use RzSDK\URL\SiteUrl;
use RzSDK\Word\Navigation\SideLink;
use RzSDK\Service\Listener\ServiceListener;
use RzSDK\Model\HTTP\Request\Search\HttpWordSearchModuleRequestModel;
use RzSDK\Service\Dictionary\Search\Module\WordSearchModuleService;
use RzSDK\Model\HTTP\Response\Search\HttpWordSearchModuleResponseModel;
use RzSDK\Log\DebugLog;
?>
<?php
class WordSearchModule {
    //
    public ServiceListener $serviceListener;
    public HttpWordSearchModuleRequestModel $searchModuleRequestModel;
    public HttpWordSearchModuleResponseModel $searchModuleResponseModel;
    private $wordLinkUrl = "";
    private $wordMeaningLinkUrl = "";
    private $limit = 10;
    //
    public function __construct(ServiceListener $serviceListener) {
        $this->serviceListener = $serviceListener;
        $this->searchModuleRequestModel = new HttpWordSearchModuleRequestModel();
        $this->searchModuleResponseModel = new HttpWordSearchModuleResponseModel();
        $this->searchModuleResponseModel->data = null;
        $this->searchModuleResponseModel->is_error = true;
        $this->searchModuleResponseModel->message = "Unknown error";
    }

    public function setWordLinkUrl($wordLinkUrl) {
        $this->wordLinkUrl = $wordLinkUrl;
        return $this;
    }

    public function setWordMeaningLinkUrl($wordMeaningLinkUrl) {
        $this->wordMeaningLinkUrl = $wordMeaningLinkUrl;
        return $this;
    }

    public function setLimit($limit = 10) {
        $this->limit = $limit;
        return $this;
    }

    public function execute($postedDataSet) {
        //DebugLog::log($postedDataSet);
        if(empty($postedDataSet)) {
            $this->searchModuleResponseModel->data = $postedDataSet;
            $this->searchModuleResponseModel->is_error = true;
            $this->searchModuleResponseModel->message = "Error! search request data empty";
            $this->serviceListener->onError($this->searchModuleResponseModel, $this->searchModuleResponseModel->message);
            return;
        }
        $postedDataSet["word_link_url"] = $this->wordLinkUrl;
        $postedDataSet["word_meaning_link_url"] = $this->wordMeaningLinkUrl;
        $postedDataSet["limit"] = $this->limit;

        //DebugLog::log($postedDataSet);
        $searchRequestQuery = $this->searchModuleRequestModel->getQuery();
        //DebugLog::log($searchRequestQuery);
        foreach($searchRequestQuery as $key => $value) {
            if(array_key_exists($key, $postedDataSet)) {
                $this->searchModuleRequestModel->$key = $postedDataSet[$key];
            } else {
                $this->searchModuleResponseModel->data = $postedDataSet;
                $this->searchModuleResponseModel->is_error = true;
                $this->searchModuleResponseModel->message = "Error! required fields are empty {$key}";
                $this->serviceListener->onError($this->searchModuleResponseModel, $this->searchModuleResponseModel->message);
                return;
            }
        }
        //DebugLog::log($this->searchModuleRequestModel);
        (new WordSearchModuleService(
            new class($this) implements ServiceListener {
                private WordSearchModule $outerInstance;

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
        ))
            ->execute($this->searchModuleRequestModel);
    }
}
?>
