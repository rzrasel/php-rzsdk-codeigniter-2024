<?php
namespace RzSDK\Universal\Language\Select\Option;
?>
<?php
use RzSDK\Universal\Language\PullLanguageList;
use RzSDK\Log\DebugLog;
?>
<?php
class BuildLanguageSelectOptions {
    //
    private $name;
    private $isAllData = false;
    private $fieldName = "language";
    private $isRequired = true;
    private $selectedLanguage;
    //

    public function __construct() {
    }

    public function setLanguageName(string $name): self {
        $this->name = $name;
        return $this;
    }
    public function setIsAllData(bool $isAllData): self {
        $this->isAllData = $isAllData;
        return $this;
    }

    public function setFieldName(string $fieldName): self {
        $this->fieldName = $fieldName;
        return $this;
    }

    public function setIsRequired(bool $isRequired): self {
        $this->isRequired = $isRequired;
        return $this;
    }

    public function setSelectedOption(string $selectedLanguage): self {
        $this->selectedLanguage = $selectedLanguage;
        return $this;
    }

    /*public function getSelectOptions() {}*/

    public function execute() {
        $languageList = $this->getDbLanguageList();
        //DebugLog::log($languageList);
        $optionsValue = "";
        $haveSelected = false;
        $optionSelected = " selected=\"select\"";
        foreach($languageList as $language) {
            $id = $language[PullLanguageList::LANGUAGE_ID];
            $name = $language[PullLanguageList::LANGUAGE_NAME];
            if($id == $this->selectedLanguage) {
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
        $optionsValue = "<option value=\"\"{$selected}>Select Language</option>\n"
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

    private function getDbLanguageList() {
        $pullLanguageList = new PullLanguageList();
        if(!empty($this->name)) {
            $pullLanguageList->setName($this->name);
        }
        if($this->isAllData) {
            $pullLanguageList->setIsAllData($this->isAllData);
        }
        $languageList = $pullLanguageList->getLanguage();
        //DebugLog::log($languageList);
        return $languageList;
    }
}
?>
