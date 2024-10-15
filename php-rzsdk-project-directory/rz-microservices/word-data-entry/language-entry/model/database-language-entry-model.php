<?php
namespace RzSDK\Model\Entry\Language;
?>
<?php
use RzSDK\DatabaseSpace\DbWordTable;
use RzSDK\Database\Word\TblLanguage;
use RzSDK\Model\HTTP\Request\Language\HttpLanguageEntryRequestModel;
use RzSDK\Identification\UniqueIntId;
use RzSDK\Utils\Id\Generator\IdGenerator;
use RzSDK\DateTime\DateTime;
use RzSDK\Log\DebugLog;
?>
<?php
class DatabaseLanguageEntryModel {
    public function __construct() {}

    public function getLanguageInsertDataSet(HttpLanguageEntryRequestModel $languageEntryRequestModel): TblLanguage {
        //
        $uniqueIntId = new UniqueIntId();
        $lanId = $uniqueIntId->getId();
        $sysUserId = IdGenerator::getSysUserId();
        $currentDate = DateTime::getCurrentDateTime();
        //
        $tblLanguage = new TblLanguage();
        $tblLanguage->lan_id = $lanId;
        $tblLanguage->lan_name = $languageEntryRequestModel->language;
        $tblLanguage->status = true;
        $tblLanguage->modified_by = $sysUserId;
        $tblLanguage->created_by = $sysUserId;
        $tblLanguage->modified_date = $currentDate;
        $tblLanguage->created_date = $currentDate;
        return $tblLanguage;
    }
}
?>
