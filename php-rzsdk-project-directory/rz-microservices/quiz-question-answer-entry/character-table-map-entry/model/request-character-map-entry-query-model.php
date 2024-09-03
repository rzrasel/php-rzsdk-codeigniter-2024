<?php
namespace RzSDK\Quiz\Model\HTTP\Request\Character\Map\Parameter;
?>
<?php
use RzSDK\Shared\HTTP\Request\Parameter\GlobalRequestParameterModel;
use RzSDK\Utils\ObjectPropertyWizard;
use RzSDK\Log\DebugLog;
?>
<?php
class RequestCharacterMapEntryQueryModel extends GlobalRequestParameterModel {
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
            $parameterModel->character_token_id,
            $parameterModel->character_name,
        );
        $parameterModel = null;
        return $this->getIndividualQuery($tempParameterArray);
    }

    public function getEntryFormName() {
        $parameterModel = new GlobalRequestParameterModel();
        $tempLanguageEntryForm = $parameterModel->character_map_entry_form;
        $parameterModel = null;
        return $tempLanguageEntryForm;
    }
}
?>
