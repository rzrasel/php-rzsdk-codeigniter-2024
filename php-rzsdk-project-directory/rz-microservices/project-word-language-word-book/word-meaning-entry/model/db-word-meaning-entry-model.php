<?php
namespace RzSDK\Model\Database\Word\Entry;
?>
<?php
use RzSDK\Identification\UniqueIntId;
use RzSDK\Utils\Id\Generator\IdGenerator;
use RzSDK\Utils\String\StringHelper;
use RzSDK\Database\Schema\TblWordMapping;
use RzSDK\Model\HTTP\Request\Parameter\Word\Meaning\Entry\RequestWordMeaningEntryQueryModel;
use RzSDK\DateTime\DateTime;
?>
<?php
class DbWordMeaningEntryModel {
    public function getWordInsertDataSet(RequestWordMeaningEntryQueryModel $wordMeaningEntryQueryModel): TblWordMapping {
        //
        $uniqueIntId = new UniqueIntId();
        $wordId = $uniqueIntId->getId();
        $sysUserId = IdGenerator::getSysUserId();
        $currentDate = DateTime::getCurrentDateTime();
        //
        $word = StringHelper::toSingleSpace($wordMeaningEntryQueryModel->word);
        $pronunciation = StringHelper::toSingleSpace($wordMeaningEntryQueryModel->pronunciation);
        $meaning = StringHelper::toSingleSpace($wordMeaningEntryQueryModel->meaning);
        $word = strtolower($word);
        //
        $tblWordMapping = new TblWordMapping();
        //
        $tblWordMapping->word_id = $wordId;
        $tblWordMapping->word = $word;
        $tblWordMapping->pronunciation = $pronunciation;
        $tblWordMapping->meaning = $meaning;
        $tblWordMapping->status = true;
        $tblWordMapping->modified_by = $sysUserId;
        $tblWordMapping->created_by = $sysUserId;
        $tblWordMapping->modified_date = $currentDate;
        $tblWordMapping->created_date = $currentDate;
        //
        return $tblWordMapping;
    }
}
?>