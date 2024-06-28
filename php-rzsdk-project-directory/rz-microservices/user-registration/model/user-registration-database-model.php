<?php
namespace RzSDK\Model\User\Registration;
?>
<?php
use RzSDK\DatabaseSpace\UserRegistrationTable;
use RzSDK\Device\ClientDevice;
use RzSDK\Device\ClientIp;
use RzSDK\Log\DebugLog;
?>
<?php
class UserRegistrationDatabaseModel {
    //

    public function getInsertSql(UserRegistrationRequestModel $userRegiRequestModel, $userId, $dateTime) {
        $userRegiTable = new UserRegistrationTable();
        $clientDevice = new ClientDevice();
        //
        $userRegiTable->user_regi_id    = $userId;
        $userRegiTable->email           = $userRegiRequestModel->email;
        $userRegiTable->status          = true;
        $userRegiTable->is_verified     = false;
        $userRegiTable->regi_date       = $dateTime;

        $userRegiTable->device_type     = $userRegiRequestModel->deviceType;
        $userRegiTable->auth_type       = $userRegiRequestModel->authType;
        $userRegiTable->agent_type      = $userRegiRequestModel->agentType;
        $userRegiTable->regi_os         = $clientDevice->getOs();
        $userRegiTable->regi_device     = $clientDevice->getDevice();

        $userRegiTable->regi_browser    = $clientDevice->getBrowser() ;
        $userRegiTable->regi_ip         = ClientIp::ip();
        $userRegiTable->regi_http_agent = $clientDevice->getHttpAgent();
        $userRegiTable->modified_by     = $userId;
        $userRegiTable->created_by      = $userId;

        $userRegiTable->modified_date   = $dateTime;
        $userRegiTable->created_date    = $dateTime;
        //
        $databaseColumn = $userRegiTable->getColumnWithKey();
        //DebugLog::log($databaseColumn);
        return $databaseColumn;
    }
}
?>