<?php
namespace RzSDK\Shared\HTTP\Request\Parameter;
?>
<?php
use RzSDK\Utils\ObjectPropertyWizard;
use RzSDK\Log\DebugLog;
?>
<?php
class GlobalRequestParameterModel {
    // Word Language Part
    public $url_word_language = "url_word_language";
    public $word_language = "word_language";
    // Word Part
    public $url_word_id = "url_word_id";
    public $url_word = "url_word";
    public $search_word = "search_word";
    public $word = "word";
    // Word Meaning Language Part
    public $url_word_meaning_language = "url_word_meaning_language";
    public $meaning_language = "meaning_language";
    // Word Meaning Part
    public $url_meaning_id = "url_meaning_id";
    public $meaning_id = "meaning_id";
    public $url_meaning = "url_meaning";
    public $meaning = "meaning";
    //
    public $word_edit_entry_form = "word_edit_entry_form";
    public $word_meaning_edit_form = "word_meaning_edit_form";
    //

    public function __construct() {
    }

    public function getQuery() {
        /*$result = array_intersect_key(
            get_object_vars($this),
            get_mangled_object_vars($this)
        );
        return array_keys($result);*/
        return ObjectPropertyWizard::getPublicVariableWithKeyValue($this);
    }

    public function toTypeCast($object): self {
        return $object;
    }

    public function getUrlParameters() {
        $retVal = "";
        $urlParameterModel = new GlobalRequestParameterModel();
        /*DebugLog::log($urlParameterModel);
        DebugLog::log($this);*/
        $defaultParameters = $urlParameterModel->getQuery();
        $queryParameters = $this->getQuery();
        /*DebugLog::log($defaultParameters);
        DebugLog::log($queryParameters);*/
        $retVal = "";
        foreach($queryParameters as $key => $value) {
            if($value != $defaultParameters[$key]) {
                $value = $queryParameters[$key];
                //$key = str_replace("_", "-", $key);
                $retVal .= "{$key}={$value}&";
            }
        }
        $retVal = trim($retVal, "&");
        //DebugLog::log($retVal);
        return $retVal;
    }

    /*public function toCastParameters(): self {
        return $object;
    }*/
}
?>
