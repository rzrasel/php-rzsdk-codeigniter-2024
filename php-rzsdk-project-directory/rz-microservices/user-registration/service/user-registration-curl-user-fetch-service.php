<?php
namespace RzSDK\Service\Adapter\User\Registration;
?>
<?php
/*require_once("../include.php");
require_once("include.php");*/
?>
<?php
use RzSDK\Curl\Curl;
use RzSDK\Service\Listener\ServiceListener;
use RzSDK\HTTPRequest\UserRegistrationRequest;
use RzSDK\Response\InfoTypeExtension;
use RzSDK\Response\InfoType;
use RzSDK\Model\User\Registration\UserInfoDatabaseModel;
use RzSDK\Model\User\Registration\UserInfoCurlResponseModel;
use RzSDK\Log\DebugLog;
?>
<?php
class UserRegistrationCurlUserFetchService {
    private ServiceListener $serviceListener;

    public function __construct(ServiceListener $serviceListener) {
        $this->serviceListener = $serviceListener;
    }

    public function execute(UserRegistrationRequest $userRegiRequest, $requestDataSet) {
        $url = PAGE_USER_INFO;
        $curl = new Curl($url);
        $result = $curl->exec(true, $requestDataSet);
        $result = json_decode($result, true);
        if(!is_array($result)) {
            $this->serviceListener->onError($requestDataSet, "Error! json error error code " . __LINE__);
            return;
        }
        $result = trim($result["body"]); // Default from curl response
        //|------------------------------------|
        //DebugLog::log($result); // Default from curl response
        //
        $curlResponse = json_decode($result, true);
        //DebugLog::log($responseData);
        if(!is_array($curlResponse)) {
            $this->serviceListener->onError($requestDataSet, "Error! json error error code " . __LINE__);
            return;
        }
        $this->curlToToDataModel($curlResponse, $requestDataSet);
    }

    private function curlToToDataModel($response, $requestDataSet) {
        //DebugLog::log($responseData);
        $responseInfoType = $response["info"]["type"];
        $responseType = InfoTypeExtension::getInfoTypeByValue($responseInfoType);
        //DebugLog::log($responseType);
        if($responseType == InfoType::ERROR) {
            $this->serviceListener->onError($requestDataSet, $response["info"]["message"] . "." . __LINE__);
            return;
        }
        $userInfoCurlResponseModel = new UserInfoCurlResponseModel();
        $userInfoCurlResponseModel->responseBody = $response["body"];
        $userInfoCurlResponseModel->infoType = $responseType;
        $this->serviceListener->onSuccess($userInfoCurlResponseModel, "Successs");
    }

    private function arrayToDataModel($response, $requestDataSet) {}
}
?>