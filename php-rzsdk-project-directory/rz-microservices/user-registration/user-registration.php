<?php
require_once("../include.php");
require_once("include.php");
?>
<?php
use RzSDK\Response\InfoType;
use RzSDK\HTTPRequest\UserRegistrationRequest;
use RzSDK\HTTPResponse\LaunchResponse;
use RzSDK\Service\Listener\ServiceListener;
use RzSDK\Service\Adapter\User\Registration\UserRegistrationRequestValidationService;
use RzSDK\Service\Adapter\User\Registration\UserRegistrationCurlUserFetchService;
use RzSDK\Model\User\Registration\UserInfoCurlResponseModel;
use RzSDK\Service\Adapter\User\Registration\UserRegistrationUserRegistrationDatabaseService;
use RzSDK\DatabaseSpace\UserRegistrationTable;
use RzSDK\Service\Adapter\User\Registration\UserRegistrationUserInfoDatabaseService;
use RzSDK\DatabaseSpace\UserInfoTable;
use RzSDK\Service\Adapter\User\Registration\UserRegistrationUserPasswordDatabaseService;
use RzSDK\DatabaseSpace\UserPasswordTable;
use RzSDK\DatabaseSpace\UserLoginAuthLogTable;
use RzSDK\Service\Adapter\User\Registration\UserAuthenticationTokenDatabaseService;
use RzSDK\Model\User\Registration\UserRegistrationResponseModel;
use RzSDK\Log\DebugLog;

?>
<?php
class UserRegistration {
    //
    private UserRegistrationRequest $userRegiRequest;
    private $postedDataSet;
    //
    public function __construct() {
        /* DebugLog::log($_SERVER["HTTP_USER_AGENT"]);
        DebugLog::log($_SERVER); */
        //$userRegiRequestModel = new UserRegistrationRequestModel();
        $this->execute();
    }

    public function execute() {
        if(!empty($_POST)) {
            $this->postedDataSet = $_POST;
            $registrationRequestValidationAction = new class($this) implements ServiceListener {
                private UserRegistration $outerInstance;

                public function __construct($outerInstance) {
                    $this->outerInstance = $outerInstance;
                }

                public function onError($dataSet, $message) {
                    $this->outerInstance->response(null, $message, InfoType::ERROR, $dataSet);
                }

                public function onSuccess($dataSet, $message) {
                    $this->outerInstance->getDbUserByCurlRequest($dataSet);
                }
            };
            //
            (new UserRegistrationRequestValidationService($registrationRequestValidationAction))
                ->execute($this->postedDataSet);
            //
            //$this->response(null, "Successful registration completed", InfoType::SUCCESS, $dataModel);
        }
    }

    public function getDbUserByCurlRequest(UserRegistrationRequest $userRegiRequest) {
        $this->userRegiRequest = $userRegiRequest;
        //$postedDataSet = $this->userRegiRequest->getQuery();
        //DebugLog::log($postedDataSet);
        (new UserRegistrationCurlUserFetchService(
            new class($this) implements ServiceListener {
                private UserRegistration $outerInstance;

                public function __construct($outerInstance) {
                    $this->outerInstance = $outerInstance;
                }

                public function onError($dataSet, $message) {
                    $this->outerInstance->response(null, $message, InfoType::ERROR, $dataSet);
                }

                public function onSuccess($dataSet, $message) {
                    $this->outerInstance->insertIntoUserRegistration($dataSet, $message);
                }
            }
        ))->execute($userRegiRequest, $this->postedDataSet);
    }

    public function insertIntoUserRegistration(UserInfoCurlResponseModel $userInfoCurlResponseModel, $message) {
        //DebugLog::log($userInfoCurlResponseModel);
        //
        if($userInfoCurlResponseModel->infoType == InfoType::DB_DATA_NOT_FOUND) {
            (new UserRegistrationUserRegistrationDatabaseService(
                new class($this) implements ServiceListener {
                    private UserRegistration $outerInstance;

                    public function __construct($outerInstance) {
                        $this->outerInstance = $outerInstance;
                    }

                    public function onError($dataSet, $message) {
                        $this->outerInstance->response(null, $message, InfoType::ERROR, $dataSet);
                    }

                    public function onSuccess($dataSet, $message) {
                        $this->outerInstance->insertIntoUserInfo($dataSet[0]);
                    }
                }
            ))->execute($this->userRegiRequest, $this->postedDataSet);
            return;
        }
        /*$lastWordStart = strrpos($message, ' ') + 1;
        $lastWord = substr($message, $lastWordStart);*/
        /*$pieces = explode(' ', $message);
        $lastWord = array_pop($pieces);*/
        $this->response(null,
            "User already exists error code " . __LINE__,
            InfoType::ERROR,
            $this->postedDataSet);
    }

    public function insertIntoUserInfo(UserRegistrationTable $userRegiTable) {
        //DebugLog::log($userRegiTable);
        (new UserRegistrationUserInfoDatabaseService(
            new class($this) implements ServiceListener {
                private UserRegistration $outerInstance;

                public function __construct($outerInstance) {
                    $this->outerInstance = $outerInstance;
                }

                public function onError($dataSet, $message) {
                    $this->outerInstance->response(null, $message, InfoType::ERROR, $dataSet);
                }

                public function onSuccess($dataSet, $message) {
                    $this->outerInstance->insertIntoUserPassword($dataSet[0]);
                }
            }
        ))->execute($userRegiTable, $this->postedDataSet);
    }

    public function insertIntoUserPassword(UserInfoTable $userInfoTable) {
        //DebugLog::log($userInfoTable);
        (new UserRegistrationUserPasswordDatabaseService(
            new class($this) implements ServiceListener {
                private UserRegistration $outerInstance;

                public function __construct($outerInstance) {
                    $this->outerInstance = $outerInstance;
                }

                public function onError($dataSet, $message) {
                    $this->outerInstance->response(null, $message, InfoType::ERROR, $dataSet);
                }

                public function onSuccess($dataSet, $message) {
                    $this->outerInstance->insertIntoUserAuthenticationToken($dataSet[0], $dataSet[1]);
                }
            }
        ))->execute($userInfoTable, $this->userRegiRequest, $this->postedDataSet);
    }

    public function insertIntoUserAuthenticationToken(UserInfoTable $userInfoTable, UserPasswordTable $userPasswordTable) {
        (new UserAuthenticationTokenDatabaseService(
            new class($this) implements ServiceListener {
                private UserRegistration $outerInstance;

                public function __construct($outerInstance) {
                    $this->outerInstance = $outerInstance;
                }

                public function onError($dataSet, $message) {
                    $this->outerInstance->response(null, $message, InfoType::ERROR, $dataSet);
                }

                public function onSuccess($dataSet, $message) {
                    $this->outerInstance->sendBackHttpResponse($dataSet[0], $dataSet[1]);
                }
            }
        ))->execute($userInfoTable, $userPasswordTable, $this->userRegiRequest);
    }

    public function sendBackHttpResponse(UserInfoTable $userInfoTable, UserLoginAuthLogTable $userLoginAuthLogTable) {
        //DebugLog::log($userInfoTable);
        //DebugLog::log($userLoginAuthLogTable);
        $userRegiResponseModel = new UserRegistrationResponseModel();
        $userRegiResponseModel->user_id = $userInfoTable->user_id;
        $userRegiResponseModel->user_email = $userInfoTable->email;
        $userRegiResponseModel->user_auth_token = $userLoginAuthLogTable->auth_token;

        $responseDataSet = $userRegiResponseModel->toParameterKeyValue();
        //DebugLog::log($responseDataSet);
        $this->response($responseDataSet,
            "Successful registration completed code " . __LINE__,
            InfoType::SUCCESS,
            $this->postedDataSet);
    }

    public function response($body, $message, InfoType $infoType, $parameter = null) {
        $launchResponse = new LaunchResponse();
        $launchResponse->setBody($body)
            ->setInfo($message, $infoType)
            ->setParameter($parameter)
            ->execute();
    }
}
?>
<?php
$userRegistration = new UserRegistration();
?>
<?php
//https://reintech.io/blog/php-password-hashing-securely-storing-verifying-passwords
?>
