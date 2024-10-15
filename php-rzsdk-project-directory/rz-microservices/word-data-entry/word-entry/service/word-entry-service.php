<?php
namespace RzSDK\Service\Entry\Activity\Language;
?>
<?php
use RzSDK\Model\HTTP\Request\Word\HttpWordEntryRequestModel;
use RzSDK\Utils\Database\Options\Language\DatabaseLanguageOptions;
use RzSDK\Log\DebugLog;
?>
<?php
class WordEntryService {
    public function getLanguageOptions($languageId = "", $defaultLanguage = "English") {
        return (new DatabaseLanguageOptions())
            ->getLanguageOptions($languageId, $defaultLanguage);
        /*$optionsList = array(
            "172157799761295096" => "Bangla Language",
            "172157831436333409" => "English Language",
        );
        //DebugLog::log($entryRequestModel->language);
        if(empty($entryRequestModel->language)) {
            $entryRequestModel->language = array_keys($optionsList)[count($optionsList) - 1];
        }
        //DebugLog::log($entryRequestModel->language);
        $retVal = "\n";
        foreach($optionsList as $key => $value) {
            if($key == $entryRequestModel->language) {
                $retVal .= "<option value=\"{$key}\" selected=\"select\">{$value}</option>\n";
            } else {
                $retVal .= "<option value=\"{$key}\">{$value}</option>\n";
            }
        }
        return $retVal;*/
    }

    public function getPartsOfSpeechOptions(HttpWordEntryRequestModel $entryRequestModel) {
        $optionsList = array(
            "" => "Select Parts of Speech",
            "adj" => "Adjective",
            "n" => "Noun",
            "adv" => "Adverb",
            "v" => "Verb",
            "vt" => "Verb Transitive",
            "vi" => "Verb Intransitive",
        );
        //$partsOfSpeechArray = array();
        $partsOfSpeechArray = $entryRequestModel->parts_of_speech;
        //DebugLog::log($partsOfSpeechArray);
        if(empty($partsOfSpeechArray)) {
            $key = array_keys($optionsList)[0];
            $partsOfSpeechArray = array(
                $key => $optionsList[$key],
            );
        }
        //DebugLog::log($partsOfSpeechArray);
        $partsOfSpeechArrayTemp = array();
        foreach($partsOfSpeechArray as $key => $value) {
            if(!is_int($key) && $key == "") {
                $partsOfSpeechArrayTemp[""] = $value;
            } else {
                $partsOfSpeechArrayTemp[$value] = $value;
            }
        }
        //DebugLog::log($partsOfSpeechArrayTemp);
        $retVal = "\n";
        foreach($optionsList as $key => $value) {
            if(array_key_exists($key, $partsOfSpeechArrayTemp)) {
                $retVal .= "<option value=\"{$key}\" selected=\"select\">{$value}</option>\n";
            } else {
                $retVal .= "<option value=\"{$key}\">{$value}</option>\n";
            }
        }
        return $retVal;
    }
}
?>