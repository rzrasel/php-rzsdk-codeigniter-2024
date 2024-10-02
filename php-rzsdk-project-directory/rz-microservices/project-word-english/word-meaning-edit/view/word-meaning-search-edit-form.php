<?php
use RzSDK\Activity\Edit\Word\Meaning\WordMeaningSearchActivity;
use RzSDK\Service\Listener\ServiceListener;
use RzSDK\Utils\Alert\Message\AlertMessageBox;
use RzSDK\Activity\Edit\Word\Meaning\WordMeaningEditActivity;
use RzSDK\Model\HTTP\Request\Parameter\Word\Meaning\Edit\RequestWordMeaningEditQueryModel;
?>
<?php
$tempTopWordMeaningEditQueryModel = new RequestWordMeaningEditQueryModel();
?>
<table border="1" width="100%">
    <tr>
        <td></td>
    </tr>
    <tr>
        <td>
<?php
$wordMeaningEditActivity = new WordMeaningEditActivity(
    new class implements ServiceListener {
        private AlertMessageBox $alertMessageBox;
        public function __construct() {
            $this->alertMessageBox = new AlertMessageBox();
        }
        public function onError($dataSet, $message) {
            //DebugLog::log($dataSet);
            //DebugLog::log($message);
            echo $this->alertMessageBox->build($message);
        }
        function onSuccess($dataSet, $message) {
            //DebugLog::log($message);
            echo $this->alertMessageBox->build($message);
            //header("Location: " . SiteUrl::getUrlOnly());
        }
    }
);
$wordMeaningEditActivity->execute($_POST);
?>
        </td>
    </tr>
    <tr>
        <td>
<?php
$searchWordId = "";
$searchWordIdParam = $tempTopWordMeaningEditQueryModel->word_id;
if(!empty($_REQUEST)) {
    if(!empty($_REQUEST[$searchWordIdParam])) {
        $searchWordId = $_REQUEST[$searchWordIdParam];
    }
}
if(!empty($searchWordId)) {
    require_once("word-meaning-edit-form.php");
}
?>
        </td>
    </tr>
    <tr>
        <td>
<?php
require_once("word-meaning-search-form.php");
?>
        </td>
    </tr>
</table>