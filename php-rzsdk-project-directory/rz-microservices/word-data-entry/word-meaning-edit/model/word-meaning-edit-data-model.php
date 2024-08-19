<?php
namespace RzSDK\Model\Database\Data\Word\Meaning\Edit;
?>
<?php
?>
<?php
use RzSDK\Shared\HTTP\Request\Parameter\RequestWordMeaningEditQueryModel;
use RzSDK\Utils\Id\Generator\IdGenerator;
use RzSDK\Database\Word\DictionaryWord;
use RzSDK\Database\Word\Meaning\DictionaryWordMeaning;
use RzSDK\DateTime\DateTime;
use RzSDK\Log\DebugLog;
?>
<?php
class WordMeaningEditDataModule {
    public function getWordMeaningUpdateDataSet(RequestWordMeaningEditQueryModel $wordMeaningEditQueryModel) {
        //
        $sysUserId = IdGenerator::getSysUserId();
        $currentDate = DateTime::getCurrentDateTime();
        //
        $tempDictionaryWordMeaning = new DictionaryWordMeaning();
        $meaningIdCol = $tempDictionaryWordMeaning->meaning_id;
        $meaningCol = $tempDictionaryWordMeaning->meaning;
        $modifiedByCol = $tempDictionaryWordMeaning->modified_by;
        $modifiedDateCol = $tempDictionaryWordMeaning->modified_date;
        $tempDictionaryWord = null;
        //
        $updateDataSet = array(
            $meaningCol => $wordMeaningEditQueryModel->meaning,
            $modifiedByCol => $sysUserId,
            $modifiedDateCol => $currentDate,
        );
        //
        return $updateDataSet;
    }
}
?>