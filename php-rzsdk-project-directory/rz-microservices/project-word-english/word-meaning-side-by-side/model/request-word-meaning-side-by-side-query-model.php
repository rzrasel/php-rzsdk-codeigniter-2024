<?php
namespace RzSDK\Model\HTTP\Request\Parameter\Word\Meaning\Side;
?>
<?php
use RzSDK\Shared\HTTP\Request\Parameter\GlobalRequestParameterModel;
use RzSDK\Utils\ObjectPropertyWizard;
?>
<?php
class RequestWordMeaningSideBySideQueryModel extends GlobalRequestParameterModel {
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
            $parameterModel->source_text,
            $parameterModel->formatted_text,
        );
        $parameterModel = null;
        return $this->getIndividualQuery($tempParameterArray);
    }

    public function getEntryFormName() {
        $parameterModel = new GlobalRequestParameterModel();
        $tempLanguageEntryForm = $parameterModel->word_meaning_side_by_side_form;
        $parameterModel = null;
        return $tempLanguageEntryForm;
    }
}
?>