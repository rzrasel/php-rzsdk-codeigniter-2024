<?php
namespace RzSDK\Quiz\Model\HTTP\Request\Book\Name\Parameter;
?>
<?php
use RzSDK\Shared\HTTP\Request\Parameter\GlobalRequestParameterModel;
use RzSDK\Utils\ObjectPropertyWizard;
?>
<?php
class RequestBookNameEntryQueryModel extends GlobalRequestParameterModel {
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

    public function getQueryParams() {
        /*$result = array_intersect_key(
            get_object_vars($this),
            get_mangled_object_vars($this)
        );*/
        $parameterModel = new GlobalRequestParameterModel();
        $tempParameterArray = array(
            $parameterModel->language_id,
            $parameterModel->book_token_id,
            $parameterModel->book_name,
            $parameterModel->book_name_is_default,
        );
        $parameterModel = null;
        return $this->getIndividualQuery($tempParameterArray);
    }

    public function getEntryFormName() {
        $parameterModel = new GlobalRequestParameterModel();
        $tempLanguageEntryForm = $parameterModel->book_name_entry_form;
        $parameterModel = null;
        return $tempLanguageEntryForm;
    }
}
?>