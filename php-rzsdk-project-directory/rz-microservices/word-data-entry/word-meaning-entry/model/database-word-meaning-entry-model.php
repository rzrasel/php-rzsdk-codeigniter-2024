<?php
namespace RzSDK\Model\Entry\Meaning;
?>
<?php
use RzSDK\DatabaseSpace\DbWordTable;
use RzSDK\Database\Word\DictionaryWord;
use RzSDK\Identification\UniqueIntId;
use RzSDK\Utils\Id\Generator\IdGenerator;
use RzSDK\Model\HTTP\Request\Word\Meaning\Entry\Model\HttpWordMeaningEntryRequestModel;
use RzSDK\Database\Word\Meaning\DictionaryWordMeaning;
use RzSDK\DateTime\DateTime;
use RzSDK\Log\DebugLog;
?>
<?php
class DatabaseWordMeaningEntryModel {
    //
    //
    public function getWordMeaningInsertDataSet(HttpWordMeaningEntryRequestModel $meaningEntryRequestModel): DictionaryWordMeaning {
        //
        $uniqueIntId = new UniqueIntId();
        $wordMeaningId = $uniqueIntId->getId();
        $sysUserId = IdGenerator::getSysUserId();
        $currentDate = DateTime::getCurrentDateTime();
        //
        $dictionaryWordMeaning  = new DictionaryWordMeaning();
        $dictionaryWordMeaning->lan_id = $meaningEntryRequestModel->meaning_language;
        $dictionaryWordMeaning->word_id = $meaningEntryRequestModel->url_word_id;
        $dictionaryWordMeaning->meaning_id = $wordMeaningId;
        $dictionaryWordMeaning->meaning = $meaningEntryRequestModel->meaning;
        $dictionaryWordMeaning->status = true;
        $dictionaryWordMeaning->modified_by = $sysUserId;
        $dictionaryWordMeaning->created_by = $sysUserId;
        $dictionaryWordMeaning->modified_date = $currentDate;
        $dictionaryWordMeaning->created_date = $currentDate;
        return $dictionaryWordMeaning;
    }
}
?>