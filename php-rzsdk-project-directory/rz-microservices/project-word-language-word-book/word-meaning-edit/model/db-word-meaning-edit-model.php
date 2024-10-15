<?php
namespace RzSDK\Model\Database\Word\Edit;
?>
<?php
use RzSDK\Identification\UniqueIntId;
use RzSDK\Utils\Id\Generator\IdGenerator;
use RzSDK\Utils\String\StringHelper;
use RzSDK\Database\Schema\TblWordMapping;
use RzSDK\Model\HTTP\Request\Parameter\Word\Meaning\Entry\RequestWordMeaningEntryQueryModel;
use RzSDK\Model\HTTP\Request\Parameter\Word\Meaning\Edit\RequestWordMeaningEditQueryModel;
use RzSDK\DateTime\DateTime;
?>
<?php
class DbWordMeaningEditModel {

    public function getWordMeaningUpdateDataSet(RequestWordMeaningEditQueryModel $wordMeaningEditQueryModel) {
        //
        $sysUserId = IdGenerator::getSysUserId();
        $currentDate = DateTime::getCurrentDateTime();
        //
        $tempTblWordMapping = new TblWordMapping();
        $colWordId = $tempTblWordMapping->word_id;
        $colWord = $tempTblWordMapping->word;
        $colPronunciation = $tempTblWordMapping->pronunciation;
        $colMeaning = $tempTblWordMapping->meaning;
        $colModifiedBy = $tempTblWordMapping->modified_by;
        $colModifiedDate = $tempTblWordMapping->modified_date;
        $tempTblWordMapping = null;
        //
        $word = StringHelper::toSingleSpace($wordMeaningEditQueryModel->word);
        $pronunciation = StringHelper::toSingleSpace($wordMeaningEditQueryModel->pronunciation);
        $meaning = StringHelper::toSingleSpace($wordMeaningEditQueryModel->meaning);
        $word = strtolower($word);
        //
        $updateDataSet = array(
            $colWord => $word,
            $colPronunciation => $pronunciation,
            $colMeaning => $meaning,
            $colModifiedBy => $sysUserId,
            $colModifiedDate => $currentDate,
        );
        //
        return $updateDataSet;
    }
}
?>