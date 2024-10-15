<?php
namespace RzSDK\Model\HTTP\Request\Word\Edit\Search;
?>
<?php
use RzSDK\Utils\ObjectPropertyWizard;
?>
<?php
class HttpWordSearchRequestModel {
    //
    public $url_word_language = "url_word_language";
    public $url_word_id = "url_word_id";
    public $search_word = "search_word";
    public $url_word = "url_word";
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