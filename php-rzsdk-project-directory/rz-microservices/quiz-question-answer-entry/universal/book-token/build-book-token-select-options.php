<?php
namespace RzSDK\Universal\Book\Token;
?>
<?php
use RzSDK\Universal\Book\Token\PullBookTokenList;
use RzSDK\Log\DebugLog;
?>
<?php
class BuildBookTokenSelectOptions {
    //
    private $name;
    private $isAllData = false;
    private $fieldName = "book-token";
    private $isRequired = true;
    private $selectedLanguage;
    //

    public function __construct() {
    }

    public function setBookTokenName(string $name): self {
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
        $languageList = $this->getDbBookTokenList();
        //DebugLog::log($languageList);
        $optionsValue = "";
        $haveSelected = false;
        $optionSelected = " selected=\"select\"";
        foreach($languageList as $language) {
            $id = $language[PullBookTokenList::BOOK_TOKEN_ID];
            $name = $language[PullBookTokenList::BOOK_TOKEN_NAME];
            if($id == $this->selectedLanguage) {
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

    private function getDbBookTokenList() {
        $pullBookTokenList = new PullBookTokenList();
        if(!empty($this->name)) {
            $pullBookTokenList->setName($this->name);
        }
        if($this->isAllData) {
            $pullBookTokenList->setIsAllData($this->isAllData);
        }
        $bookTokenList = $pullBookTokenList->getBookToken();
        //DebugLog::log($bookTokenList);
        return $bookTokenList;
    }
}
?>
