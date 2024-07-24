<?php
namespace RzSDK\Model\HTTP\Request\Word;
?>
<?php
use RzSDK\Utils\ObjectPropertyWizard;
?>
<?php
class HttpWordEntryRequestModel {
    //
    public $language = "language";
    public $word = "word";
    public $pronunciation = "pronunciation";
    public $accent_us = "accent_us";
    public $accent_uk = "accent_uk";
    public $parts_of_speech = "parts_of_speech";
    public $syllable = "syllable";
    public $force_entry = "force_entry";
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