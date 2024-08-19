<?php
namespace RzSDK\Model\Entry\Language;
?>
<?php
use RzSDK\DatabaseSpace\DbWordTable;
use RzSDK\Database\Word\DictionaryWord;
use RzSDK\Identification\UniqueIntId;
use RzSDK\Utils\Id\Generator\IdGenerator;
use RzSDK\Model\HTTP\Request\Word\HttpWordEntryRequestModel;
use RzSDK\DateTime\DateTime;
use RzSDK\Log\DebugLog;
?>
<?php
class DatabaseWordEntryModel {
    public function getWordInsertDataSet(HttpWordEntryRequestModel $wordEntryRequestModel): DictionaryWord {
        //
        $uniqueIntId = new UniqueIntId();
        $wordId = $uniqueIntId->getId();
        $sysUserId = IdGenerator::getSysUserId();
        $currentDate = DateTime::getCurrentDateTime();
        //
        $dictionaryWord  = new DictionaryWord();
        $dictionaryWord->lan_id = $wordEntryRequestModel->language;
        $dictionaryWord->word_id = $wordId;
        $dictionaryWord->word = $wordEntryRequestModel->word;
        $dictionaryWord->pronunciation = $wordEntryRequestModel->pronunciation;
        $dictionaryWord->accent_us = $wordEntryRequestModel->accent_us;
        $dictionaryWord->accent_uk = $wordEntryRequestModel->accent_uk;
        $dictionaryWord->parts_of_speech = implode(",", $wordEntryRequestModel->parts_of_speech);
        $dictionaryWord->syllable = $wordEntryRequestModel->syllable;
        $dictionaryWord->status = true;
        $dictionaryWord->modified_by = $sysUserId;
        $dictionaryWord->created_by = $sysUserId;
        $dictionaryWord->modified_date = $currentDate;
        $dictionaryWord->created_date = $currentDate;
        return $dictionaryWord;
    }
}
?>