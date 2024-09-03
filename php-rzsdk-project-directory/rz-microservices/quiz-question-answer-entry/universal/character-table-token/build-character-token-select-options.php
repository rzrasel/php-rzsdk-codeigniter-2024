<?php
namespace RzSDK\Universal\Character\Token;
?>
<?php
use RzSDK\Universal\Character\Token\PullCharacterTokenList;
use RzSDK\Log\DebugLog;
?>
<?php
class BuildCharacterTokenSelectOptions {
    //
    private $fieldName = "character-token";
    private $isRequired = true;
    private $selectedCharacterToken;
    //

    public function setFieldName(string $fieldName): self {
        $this->fieldName = $fieldName;
        return $this;
    }

    public function setIsRequired(bool $isRequired): self {
        $this->isRequired = $isRequired;
        return $this;
    }

    public function setSelectedOption(string $selectedCharacterToken): self {
        $this->selectedCharacterToken = $selectedCharacterToken;
        return $this;
    }

    public function execute() {
        $characterTokenList = $this->getDbCharacterTokenList();
        //DebugLog::log($characterList);
        $optionsValue = "";
        $haveSelected = false;
        $optionSelected = " selected=\"select\"";
        foreach($characterTokenList as $characterToken) {
            $id = $characterToken[PullCharacterTokenList::TOKEN_ID];
            $name = $characterToken[PullCharacterTokenList::TOKEN_NAME];
            if($id == $this->selectedCharacterToken) {
                $optionSelected = " selected=\"select\"";
                $haveSelected = true;
            } else {
                $optionSelected = "";
            }
            $optionsValue .= $this->getOptionField($id, $name, $optionSelected);
        }
        $optionsValue = $this->getDefaultOptionField($haveSelected, $optionsValue);
        $selectValue = $this->getSelectOptionField($optionsValue);
        return $selectValue;
    }

    private function getOptionField($id, $name, $selected = "") {
        $optionsValue = "<option value=\"{$id}\"{$selected}>{$name}</option>\n";
        return $optionsValue;
    }

    private function getDefaultOptionField($haveSelected, $options) {
        $selected = " selected=\"select\"";
        if($haveSelected) {
            $selected = "";
        }
        $optionsValue = "<option value=\"\"{$selected}>Select Book Token</option>\n"
            . $options;
        return $optionsValue;
    }

    private function getSelectOptionField($options) {
        $required = "";
        if($this->isRequired) {
            $required = "required=\"required\"";
        }
        $selectValue = "\n<select name=\""
            . $this->fieldName
            . "\" "
            . $required
            . ">\n"
            . $options
            . "</select>\n";
        return $selectValue;
    }

    private function getDbCharacterTokenList() {
        $pullCharacterTokenList = new PullCharacterTokenList();
        $bookTokenList = $pullCharacterTokenList->getCharacterToken();
        //DebugLog::log($bookTokenList);
        return $bookTokenList;
    }
}
?>