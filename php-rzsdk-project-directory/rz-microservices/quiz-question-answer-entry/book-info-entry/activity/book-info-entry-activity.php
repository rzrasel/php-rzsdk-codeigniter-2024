<?php
namespace RzSDK\Quiz\Activity\Book\Information\Entry;
?>
<?php
use RzSDK\Service\Listener\ServiceListener;
use RzSDK\Quiz\Model\HTTP\Request\Book\Information\Parameter\RequestBookInfoEntryQueryModel;
use RzSDK\Log\DebugLog;
?>
<?php
class BookInfoEntryActivity {
    //
    public ServiceListener $serviceListener;
    public RequestBookInfoEntryQueryModel $bookInfoEntryQueryModel;
    //
    public function __construct(ServiceListener $serviceListener) {
        $this->serviceListener = $serviceListener;
        $this->bookInfoEntryQueryModel = new RequestBookInfoEntryQueryModel();
    }

    public function execute($postedDataSet) {
        //DebugLog::log($postedDataSet);
        $queryParams = $this->bookInfoEntryQueryModel->getQueryParams();
        //DebugLog::log($queryParams);
        if (empty($postedDataSet)) {
            foreach ($queryParams as $key => $value) {
                $this->bookInfoEntryQueryModel->$key = "";
            }
            //$this->bookNameEntryQueryModel->book_name_is_default = false;
            return;
        }
    }
}
?>
