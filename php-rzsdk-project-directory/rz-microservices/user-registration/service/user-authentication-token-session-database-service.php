<?php
namespace RzSDK\Service\Adapter\User\Registration;
?>
<?php
/*require_once("../include.php");
require_once("include.php");*/
?>
<?php
use RzSDK\Service\Listener\ServiceListener;
use RzSDK\DatabaseSpace\UserInfoTable;
use RzSDK\HTTPRequest\UserRegistrationRequest;
use RzSDK\DatabaseSpace\UserPasswordTable;
use RzSDK\Database\SqliteConnection;
use RzSDK\DatabaseSpace\DbUserTable;
use RzSDK\SqlQueryBuilder\SqlQueryBuilder;
use RzSDK\Log\DebugLog;
?>
<?php
class UserAuthenticationTokenSessionDatabaseService {
    private ServiceListener $serviceListener;

    public function __construct(ServiceListener $serviceListener) {
        $this->serviceListener = $serviceListener;
    }

    public function execute(UserInfoTable $userInfoTable, UserPasswordTable $userPasswordTable, UserRegistrationRequest $userRegiRequest) {
        DebugLog::log($userInfoTable);
        DebugLog::log($userPasswordTable);
    }
}
?>