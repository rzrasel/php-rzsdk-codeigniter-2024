<?php
namespace RzSDK\Model\HTTP\Request\Language;
?>
<?php
use RzSDK\Utils\ObjectPropertyWizard;
?>
<?php
class HttpLanguageEntryRequestModel {
    //
    public $language = "language";
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
