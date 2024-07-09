<?php
require_once("../include.php");
require_once("include.php");
?>
<?php
use RzSDK\HTTPResponse\LaunchResponse;
use RzSDK\Response\InfoType;
use RzSDK\Model\User\Authentication\UserAuthTokenAuthenticationRequestModel;
use RzSDK\Log\DebugLog;
?>
<?php
class UserAuthTokenAuthenticationToken {
    private UserAuthTokenAuthenticationRequestModel $userAuthTokenAuthRequestModel;
    private $postedDataSet;

    public function __construct(){
        $this->execute();
    }

    public function execute() {
        if(!empty($_POST)) {
            //DebugLog::log($_POST);
            $this->userAuthTokenAuthRequestModel = new UserAuthTokenAuthenticationRequestModel();
            $requestKeyValueModel = $this->userAuthTokenAuthRequestModel->getPropertyKeyValue();
            //DebugLog::log($requestModelKeyValue);
            foreach($requestKeyValueModel as $key => $value) {
                if(array_key_exists($key, $_POST)) {
                    $this->userAuthTokenAuthRequestModel->$key = $_POST[$key];
                } else {
                    $this->response(null, "Error! request parameter not matched out of type", InfoType::ERROR, $_POST);
                    return;
                }
            }
            $this->postedDataSet = $this->userAuthTokenAuthRequestModel->getPropertyKeyValue();
            DebugLog::log($this->postedDataSet);
        }
    }

    private function response($body, $message, InfoType $infoType, $parameter = null) {
        $launchResponse = new LaunchResponse();
        $launchResponse->setBody($body)
            ->setInfo($message, $infoType)
            ->setParameter($parameter)
            ->execute();
    }
}
?>
<?php
$userAuthTokenAuthenticationToken = new UserAuthTokenAuthenticationToken();
?>
