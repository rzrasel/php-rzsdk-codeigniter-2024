<?php
namespace RzSDK\Shared\HTTP\Request\Parameter;
?>
<?php
//require_once("global-url-parameter-model.php");
?>
<?php
use RzSDK\Utils\ObjectPropertyWizard;
use RzSDK\Shared\HTTP\Request\Parameter\GlobalRequestParameterModel;
use RzSDK\Log\DebugLog;
?>
<?php
class RequestWordMeaningEditQueryModel extends GlobalRequestParameterModel {
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

    public function getWordMeaningEditQuery() {
        /*$result = array_intersect_key(
            get_object_vars($this),
            get_mangled_object_vars($this)
        );*/
        $parameterModel = new GlobalRequestParameterModel();
        $retParameterArray = array(
            $parameterModel->url_word_language,
            $parameterModel->word_language,
            $parameterModel->url_word_id,
            $parameterModel->url_word,
            $parameterModel->search_word,
            $parameterModel->word,
            $parameterModel->url_word_meaning_language,
            $parameterModel->meaning_language,
            $parameterModel->url_meaning_id,
            $parameterModel->meaning_id,
            $parameterModel->url_meaning,
            $parameterModel->meaning,
            $parameterModel->word_meaning_edit_form,
        );
        $parameterModel = null;
        return $retParameterArray;
    }

    public function getWordMeaningEditFormField() {
        $parameterModel = new GlobalRequestParameterModel();
        $tempWordMeaningEditForm = $parameterModel->word_meaning_edit_form;
        $parameterModel = null;
        return $tempWordMeaningEditForm;
    }
}
?>