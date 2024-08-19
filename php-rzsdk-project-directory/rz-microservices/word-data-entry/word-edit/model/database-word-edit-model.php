<?php
namespace RzSDK\Model\Database\Data\Word\Edit;
?>
<?php
?>
<?php
use RzSDK\Model\HTTP\Request\Word\Edit\HttpWordEditRequestModel;
use RzSDK\Utils\Id\Generator\IdGenerator;
use RzSDK\Database\Word\DictionaryWord;
use RzSDK\DateTime\DateTime;
use RzSDK\Log\DebugLog;
?>
<?php
class WordEditDataModule {
    public function getWordUpdateDataSet(HttpWordEditRequestModel $wordEntryRequestModel) {
        //
        $sysUserId = IdGenerator::getSysUserId();
        $currentDate = DateTime::getCurrentDateTime();
        //
        $tempDictionaryWord = new DictionaryWord();
        $wordIdCol = $tempDictionaryWord->word_id;
        $wordCol = $tempDictionaryWord->word;
        $pronunciationCol = $tempDictionaryWord->pronunciation;
        $accentUsCol = $tempDictionaryWord->accent_us;
        $accentUkCol = $tempDictionaryWord->accent_uk;
        $partsOfSpeechCol = $tempDictionaryWord->parts_of_speech;
        $syllableCol = $tempDictionaryWord->syllable;
        $modifiedByCol = $tempDictionaryWord->modified_by;
        $modifiedDateCol = $tempDictionaryWord->modified_date;
        $tempDictionaryWord = null;
        //
        $updateDataSet = array(
            $wordCol => $wordEntryRequestModel->word,
            $pronunciationCol => $wordEntryRequestModel->pronunciation,
            $accentUsCol => $wordEntryRequestModel->accent_us,
            $accentUkCol => $wordEntryRequestModel->accent_uk,
            $partsOfSpeechCol => implode(",", $wordEntryRequestModel->parts_of_speech),
            $syllableCol => $wordEntryRequestModel->syllable,
            $modifiedByCol => $sysUserId,
            $modifiedDateCol => $currentDate,
        );
        //
        return $updateDataSet;
    }
}
?>