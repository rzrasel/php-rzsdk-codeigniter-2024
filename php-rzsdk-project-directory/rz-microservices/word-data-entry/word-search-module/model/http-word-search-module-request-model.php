<?php
namespace RzSDK\Model\HTTP\Request\Search;
?>
<?php
use RzSDK\Utils\ObjectPropertyWizard;
?>
<?php
class HttpWordSearchModuleRequestModel {
    //
    public $word_language = "word_language";
    public $search_word = "search_word";
    //
    public $word_link_url;
    public $limit = 10;
    //
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