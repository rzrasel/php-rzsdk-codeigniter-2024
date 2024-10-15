<?php
namespace RzSDK\Model\Url\Word\Meaning\Parameter;
?>
<?php
use RzSDK\Utils\ObjectPropertyWizard;
use RzSDK\Log\DebugLog;
?>
<?php
class UrlWordMeaningParameter {
    //
    public $url_word_language   = "url_word_language";
    public $url_word_id     = "url_word_id";
    public $url_word        = "url_word";
    public $search_word     = "search_word";
    //
    public $url_word_meaning_language   = "url_word_meaning_language";
    public $url_meaning_id  = "url_meaning_id";
    public $url_meaning     = "url_meaning";
    //
    public function getQuery() {
        /*$result = array_intersect_key(
            get_object_vars($this),
            get_mangled_object_vars($this)
        );
        return array_keys($result);*/
        return ObjectPropertyWizard::getPublicVariableWithKeyValue($this);
    }

    public function toQueryParameter() {
        $paramList = $this->getQuery();
        //DebugLog::log($paramList);
        $retVal = "";
        foreach($paramList as $key => $value) {
            $retVal .= $key . "=" . $value . "&";
        }
        return trim($retVal, "&");
    }

    public function toTypeCast($object): self {
        return $object;
    }
}
?>