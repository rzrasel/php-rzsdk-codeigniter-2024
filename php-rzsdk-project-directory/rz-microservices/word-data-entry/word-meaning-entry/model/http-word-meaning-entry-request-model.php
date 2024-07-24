<?php
namespace RzSDK\Model\HTTP\Request\Word\Meaning\Entry\Model;
?>
<?php
use RzSDK\Utils\ObjectPropertyWizard;
use RzSDK\Log\DebugLog;
?>
<?php
class HttpWordMeaningEntryRequestModel {
    //
    public $word_language = "word_language";
    public $url_word_id = "url_word_id";
    public $search_word = "search_word";
    public $url_word = "url_word";
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
}
?>
