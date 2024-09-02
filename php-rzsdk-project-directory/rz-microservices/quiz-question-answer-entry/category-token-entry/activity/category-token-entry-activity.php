<?php
namespace RzSDK\Quiz\Activity\Category\Token\Entry;
?>
<?php
use RzSDK\Service\Listener\ServiceListener;
use RzSDK\Quiz\Model\HTTP\Request\Category\Token\Parameter\RequestCategoryTokenEntryQueryModel;
use RzSDK\Log\DebugLog;
?>
<?php
class CategoryTokenEntryActivity {
    //
    public ServiceListener $serviceListener;
    public RequestCategoryTokenEntryQueryModel $categoryTokenEntryQueryModel;
    //

    public function __construct(ServiceListener $serviceListener) {
        $this->serviceListener = $serviceListener;
        $this->categoryTokenEntryQueryModel = new RequestCategoryTokenEntryQueryModel();
    }

    public function execute($postedDataSet) {
        $queryParams = $this->categoryTokenEntryQueryModel->getQueryParams();
        //DebugLog::log($queryParams);
        if(empty($postedDataSet)) {
            foreach($queryParams as $key => $value) {
                $this->categoryTokenEntryQueryModel->$key = "";
            }
            return;
        }
        if(!array_key_exists($this->categoryTokenEntryQueryModel->getEntryFormName(), $postedDataSet)) {
            return;
        }
        DebugLog::log($postedDataSet);
        foreach($queryParams as $key => $value) {
            //echo $key . ": " . $value . "\n";
            if(array_key_exists($value, $postedDataSet)) {
                $this->categoryTokenEntryQueryModel->$key = $postedDataSet[$value];
            }
        }
        DebugLog::log($this->categoryTokenEntryQueryModel->getUrlParameters());
    }
}
?>
