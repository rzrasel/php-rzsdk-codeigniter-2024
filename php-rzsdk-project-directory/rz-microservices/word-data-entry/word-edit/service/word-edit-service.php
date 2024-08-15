<?php
namespace RzSDK\Service\Entry\Activity\Word;
?>
<?php
use RzSDK\Utils\Database\Options\Language\DatabaseLanguageOptions;
use RzSDK\Model\HTTP\Request\Word\Edit\HttpWordEditRequestModel;
use RzSDK\Utils\ArrayUtils;
use RzSDK\Log\DebugLog;
?>
<?php
class WordEditService {
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

    //public function getPartsOfSpeechOptions(HttpWordEditRequestModel $entryRequestModel) {
    public function getPartsOfSpeechOptions(array $requestedOptionList) {
        //DebugLog::log($optionList);
        $optionsList = array(
            "" => "Select Parts of Speech",
            "adj" => "Adjective",
            "n" => "Noun",
            "adv" => "Adverb",
            "v" => "Verb",
            "vi" => "Verb Intransitive",
            "vt" => "Verb Transitive",
        );
        //$partsOfSpeechArray = array();
        $partsOfSpeechArray = $requestedOptionList;
        //DebugLog::log($partsOfSpeechArray);
        if(empty($partsOfSpeechArray)) {
            $key = array_keys($optionsList)[0];
            $partsOfSpeechArray = array(
                $key => $optionsList[$key],
            );
        }
        $partsOfSpeechArrayTemp = $this->getSelectedOptionsList($requestedOptionList, $optionsList);
        //DebugLog::log($partsOfSpeechArrayTemp);
        //DebugLog::log($partsOfSpeechArray);
        /*$partsOfSpeechArrayTemp = array();
        foreach($partsOfSpeechArray as $key => $value) {
            if(!is_int($key) && $key == "") {
                $partsOfSpeechArrayTemp[""] = $value;
            } else {
                $partsOfSpeechArrayTemp[$value] = $value;
            }
        }*/
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

    private function getSelectedOptionsList(array $requestedOptionList, array $optionsList) {
        //DebugLog::log($requestedOptionList);
        $retValue = array();
        if(empty($requestedOptionList)) {
            $key = array_keys($optionsList)[0];
            $retValue = array(
                $key => $optionsList[$key],
            );
            return $retValue;
        }
        if(ArrayUtils::isAssociative($requestedOptionList)) {
            foreach($requestedOptionList as $key => $value) {
                $key = strtolower($key);
                if(array_key_exists($key, $optionsList)) {
                    if(!is_int($key) && $key == "") {
                        $retValue[""] = $key;
                    } else {
                        $retValue[$key] = $key;
                    }
                }
            }
            return $retValue;
        }
        //
        foreach($requestedOptionList as $value) {
            $value = ucwords(strtolower($value));
            $index = array_search($value, $optionsList);
            //DebugLog::log($index . " =============================");
            if(!empty($index)) {
                //DebugLog::log("++++++++++++++++++");
                $retValue[$index] = $optionsList[$index];
            }
            /*if(in_array($value, $optionsList)) {
                DebugLog::log("++++++++++++++++++");
            }*/
        }
        if(empty($retValue)) {
            foreach($requestedOptionList as $key) {
                $key = strtolower($key);
                if(array_key_exists($key, $optionsList)) {
                    $retValue[$key] = $optionsList[$key];
                }
            }
        }
        return $retValue;
    }
}
?>
