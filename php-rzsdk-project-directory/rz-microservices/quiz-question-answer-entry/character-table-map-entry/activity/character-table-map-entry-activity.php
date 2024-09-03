<?php
namespace RzSDK\Quiz\Activity\Category\Map\Entry;
?>
<?php
use RzSDK\Service\Listener\ServiceListener;
use RzSDK\Quiz\Model\HTTP\Request\Category\Token\Parameter\RequestCategoryTokenEntryQueryModel;
use RzSDK\Quiz\Model\HTTP\Request\Character\Map\Parameter\RequestCharacterMapEntryQueryModel;
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
            //$this->bookNameEntryQueryModel->book_name_is_default = false;
            return;
        }
    }
}
?>
