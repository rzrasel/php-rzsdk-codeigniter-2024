<?php
namespace RzSDK\Word\Meaning\Edit\Search\Word\Activity;
?>
<?php
use RzSDK\Model\HTTP\Request\Word\Edit\Search\HttpWordSearchRequestModel;
use RzSDK\Service\Listener\ServiceListener;
use RzSDK\Dictionary\Search\Module\WordSearchModule;
use RzSDK\URL\SiteUrl;
use RzSDK\Service\Word\Meaning\Edit\Activity\Search\WordSearchActivityService;
use RzSDK\Utils\Database\Options\Language\DatabaseLanguageOptions;
use RzSDK\Log\DebugLog;
?>
<?php
class WordSearchActivity {
    public HttpWordSearchRequestModel $wordSearchRequestModel;
    public $responseData;
    private $defaultLanguageName = "English";
    private $limit = 50;
    private $wordUrl;
    private $wordMeaningUrl;
    //
    public function __construct() {
        //$this->execute($_POST);
    }

    public function setLimit($limit = 50) {
        $this->limit = $limit;
        return $this;
    }

    public function setWordUrl($wordUrl) {
        if(empty($wordUrl)) {
            $wordUrl = SiteUrl::getBaseUrl();
        }
        $this->wordUrl = $wordUrl;
        return $this;
    }

    public function setWordMeaningUrl($wordMeaningUrl) {
        if(empty($wordMeaningUrl)) {
            $wordMeaningUrl = SiteUrl::getBaseUrl();
        }
        $this->wordMeaningUrl = $wordMeaningUrl;
        return $this;
    }

    public function execute() {
        $postedDataSet = $_POST;
        $this->wordSearchRequestModel = new HttpWordSearchRequestModel();
        if(empty($postedDataSet)) {
            $languageId = (new DatabaseLanguageOptions())
                ->getLanguageIdByName($this->defaultLanguageName);
            $postedDataSet["url_word_language"] = $languageId;
            $postedDataSet["search_word"] = "";
            if(!empty($_REQUEST["search_word"])) {
                $postedDataSet["search_word"] = $_REQUEST["search_word"];
            }
        }
        //DebugLog::log($postedDataSet);
        $wordSearchQuery = $this->wordSearchRequestModel->getQuery();
        //DebugLog::log($wordSearchQuery);
        foreach($wordSearchQuery as $key => $value) {
            if(array_key_exists($key, $postedDataSet)) {
                $this->wordSearchRequestModel->$key = $postedDataSet[$key];
            } else {
                $this->wordSearchRequestModel->$key = null;
            }
        }
        //DebugLog::log($this->wordSearchRequestModel);
        $this->doSearch($this->wordSearchRequestModel);
    }

    private function doSearch(HttpWordSearchRequestModel $wordSearchRequestModel) {
        (new WordSearchModule(
            new class($this) implements ServiceListener {
                private WordSearchActivity $outerInstance;

                // Constructor to receive outer instance
                public function __construct($outerInstance) {
                    $this->outerInstance = $outerInstance;
                }

                public function onError($dataSet, $message) {
                    //DebugLog::log($dataSet);
                    //DebugLog::log($message);
                    $this->outerInstance->responseData = $message;
                }

                function onSuccess($dataSet, $message) {
                    //DebugLog::log($dataSet);
                    //DebugLog::log($message);
                    $this->outerInstance->responseData = $dataSet->data;
                    //DebugLog::log($this->outerInstance->responseData);
                }
            }
        ))
            ->setWordLinkUrl($this->wordUrl)
            ->setWordMeaningLinkUrl($this->wordMeaningUrl)
            ->setLimit($this->limit)
            ->execute($wordSearchRequestModel->getQuery());
        //DebugLog::log($this->limit);
        $this->reAssignRequestData($_REQUEST);
    }

    private function reAssignRequestData($postedDataSet) {
        //DebugLog::log($postedDataSet);
        if(empty($postedDataSet)) {
            $languageId = (new DatabaseLanguageOptions())
                ->getLanguageIdByName($this->defaultLanguageName);
            $postedDataSet["url_word_language"] = $languageId;
            $postedDataSet["search_word"] = "";
        }
        $wordSearchQuery = $this->wordSearchRequestModel->getQuery();
        //DebugLog::log($wordSearchQuery);
        foreach($wordSearchQuery as $key => $value) {
            if(array_key_exists($key, $postedDataSet)) {
                $this->wordSearchRequestModel->$key = $postedDataSet[$key];
            } else {
                $this->wordSearchRequestModel->$key = null;
            }
        }
        //DebugLog::log($this->wordSearchRequestModel);
    }

    public function getWordLanguageOptions($languageId = "", $defaultLanguage = "English") {
        return (new WordSearchActivityService())
            ->getWordLanguageOptions($languageId, $defaultLanguage);
    }

    public function getMeaningLanguageOptions($languageId = "", $defaultLanguage = "Bangla") {
        return (new WordSearchActivityService())
            ->getMeaningLanguageOptions($languageId, $defaultLanguage);
    }
}
?>