<?php
namespace RzSDK\Shared\HTTP\Request\Parameter;
?>
<?php
use RzSDK\Utils\ObjectPropertyWizard;
use RzSDK\Log\DebugLog;
?>
<?php
class GlobalRequestParameterModel {
    // Language Request Query Part
    public $language_name = "language-name";
    public $category_token_name = "category-token-name";
    public $book_token_name = "book-token-name";
    //
    public $language_entry_form = "language-entry-form";
    public $category_token_entry_form = "category-token-entry-form";
    public $book_token_entry_form = "book-token-entry-form";
    //

    public function __construct() {
    }

    public function getReplaceCharacter() {
        return array("_", "-");
    }

    public function getQuery() {
        /*$result = array_intersect_key(
            get_object_vars($this),
            get_mangled_object_vars($this)
        );
        return array_keys($result);*/
        return ObjectPropertyWizard::getPublicVariableWithKeyValue($this);
    }

    protected function getIndividualQuery($parameterQueries = array()) {
        $retParameterArray = array();
        $parameterModel = new GlobalRequestParameterModel();
        $replaceCharacter = $this->getReplaceCharacter();
        foreach($parameterQueries as $value) {
            //$value = str_replace($replaceCharacter[0], $replaceCharacter[1], $key);
            $key = str_replace($replaceCharacter[1], $replaceCharacter[0], $value);
            $retParameterArray[$key] = $value;
        }
        $parameterModel = null;
        return $retParameterArray;
    }

    public function toTypeCast($object): self {
        return $object;
    }

    public function getUrlParameters() {
        $retVal = "";
        $urlParameterModel = new GlobalRequestParameterModel();
        /*DebugLog::log($urlParameterModel);
        DebugLog::log($this);*/
        $defaultParameters = $urlParameterModel->getQuery();
        $queryParameters = $this->getQuery();
        /*DebugLog::log($defaultParameters);
        DebugLog::log($queryParameters);*/
        $retVal = "";
        $replaceCharacter = $this->getReplaceCharacter();
        foreach($queryParameters as $key => $value) {
            if (str_contains($key, "form")) {
                continue;
            }
            if($value != $defaultParameters[$key]) {
                $value = $queryParameters[$key];
                $key = str_replace($replaceCharacter[0], $replaceCharacter[1], $key);
                $retVal .= "{$key}={$value}&";
            }
        }
        $retVal = trim($retVal, "&");
        //DebugLog::log($retVal);
        return $retVal;
    }

    /*public function toCastParameters(): self {
        return $object;
    }*/
}
?>
