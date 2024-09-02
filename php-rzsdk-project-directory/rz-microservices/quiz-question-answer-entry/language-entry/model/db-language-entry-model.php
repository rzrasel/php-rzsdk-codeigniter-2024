<?php
namespace RzSDK\Quiz\Model\Database\Language\Entry;
?>
<?php
use RzSDK\Identification\UniqueIntId;
use RzSDK\DateTime\DateTime;
use RzSDK\Utils\Id\Generator\IdGenerator;
use RzSDK\Database\Quiz\TblLanguage;
use RzSDK\Quiz\Model\HTTP\Request\Language\Parameter\RequestLanguageEntryQueryModel;
use RzSDK\Log\DebugLog;
?>
<?php
class DbLanguageEntryModel {
    public function getLanguageInsertDataSet(RequestLanguageEntryQueryModel $languageEntryQueryModel): TblLanguage {
        //
        $uniqueIntId = new UniqueIntId();
        $languageId = $uniqueIntId->getId();
        $sysUserId = IdGenerator::getSysUserId();
        $currentDate = DateTime::getCurrentDateTime();
        //
        $languageName = trim($languageEntryQueryModel->language_name);
        $languageSlug = strtolower($languageName);
        $languageSlug = str_replace(" ","-", $languageSlug);
        //
        $tblLanguage = new TblLanguage();
        //
        $tblLanguage->lan_id = $languageId;
        $tblLanguage->lan_name = $languageName;
        $tblLanguage->slug = $languageSlug;
        $tblLanguage->status = true;
        $tblLanguage->modified_by = $sysUserId;
        $tblLanguage->created_by = $sysUserId;
        $tblLanguage->modified_date = $currentDate;
        $tblLanguage->created_date = $currentDate;
        //
        return $tblLanguage;
    }
}
?>
