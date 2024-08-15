<?php
namespace RzSDK\Shared\HTTP\Url\Parameter;
?>
<?php
use RzSDK\Utils\ObjectPropertyWizard;
use RzSDK\Log\DebugLog;
?>
<?php
class GlobalUrlParameterModel {
    //
    public $url_word_language = "url_word_language";
    public $url_word_id = "url_word_id";
    public $search_word = "search_word";
    public $url_word = "url_word";
    //
    public $meaning_language = "meaning_language";
    public $meaning = "meaning";
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
        $urlParameterModel = new GlobalUrlParameterModel();
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
