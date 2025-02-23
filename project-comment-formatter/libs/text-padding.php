<?php
namespace RzSDK\String\Utils;
?>
<?php
require_once("string-utils.php");
require_once("text-case.php");
require_once("padding-place.php");
require_once("text-padding/padding-property.php");
require_once("text-padding/set-source-text.php");
require_once("text-padding/set-full-length.php");
require_once("text-padding/set-number-of-tabs.php");
require_once("text-padding/set-padding-character.php");
require_once("text-padding/set-text-case.php");
require_once("text-padding/set-padding-edge.php");
?>
<?php
use RzSDK\Padding\Property\PaddingProperty;
use RzSDK\Padding\Property\SetSourceText;
use RzSDK\Padding\Property\SetFullLength;
use RzSDK\Padding\Property\SetNumberOfTabs;
use RzSDK\Padding\Property\SetPaddingCharacter;
use RzSDK\Padding\Property\SetTextCase;
use RzSDK\Padding\Property\SetPaddingEdge;
use RzSDK\Padding\Utils\PaddingPlace;
?>
<?php
//|---------------------|TextPadding CLASS|----------------------|
class TextPadding extends PaddingProperty {
    //|------------------|CLASS VARIABLE SCOPE|------------------|
    use SetSourceText, SetFullLength;
    use SetNumberOfTabs;
    use SetPaddingCharacter;
    use SetTextCase, SetPaddingEdge;

    //|---------------------|execute METHOD|---------------------|
    public function execute() {
        $this->sourceText = StringUtils::removeWhitespace($this->sourceText);
        $this->sourceText = StringUtils::toCaseConversion($this->sourceText, $this->textCase);
        $this->sourceText = "|{$this->sourceText}|";
        //echo $this->sourceText;
        $this->calculatePadding();
    }

    //|----------------|getFormattedText METHOD|-----------------|
    public function getFormattedText() {
        return $this->formattedText;
    }

    //|----------------|calculatePadding METHOD|-----------------|
    private function calculatePadding() {
        //
        $this->tempFullLength = $this->fullLength;
        $this->tempFullLength = $this->tempFullLength - ($this->tabCount * $this->tabSpace);
        //
        //$this->remainingLength = $this->fullLength - strlen($this->sourceText);
        $this->remainingLength = $this->fullLength - mb_strlen($this->sourceText);
        $this->remainingLength = $this->remainingLength - ($this->tabCount * $this->tabSpace);
        //
        if($this->remainingLength < 0) {
            $this->remainingLength = 0;
        }
        //echo ceil(5 / 2);
        /*echo "<br />";
        echo $this->remainingLength;*/
        //$this->addPadding();
        $this->setPadding($this->paddingWings);
    }

    //|-------------------|addPadding METHOD|--------------------|

    private function addPadding() {
        /*echo "<br />";
        echo $this->paddingWings->value;*/
        if($this->paddingWings == PaddingPlace::CENTER) {
            $this->setPaddingBoth();
        } else if($this->paddingWings == PaddingPlace::LEFT) {
            $this->setPaddingLeft();
        } else if($this->paddingWings == PaddingPlace::RIGHT) {
            $this->setPaddingRight();
        }
        /*echo "<br />";
        echo $this->formattedText;*/
    }

    //|-----------------|setPaddingLeft METHOD|------------------|
    private function setPaddingLeft() {
        $text = $this->sourceText;
        $padding = $this->padString;
        $length = $this->remainingLength;
        $fullLength = $this->tempFullLength - 1;
        $this->formattedText = str_pad($text, $fullLength, $padding, STR_PAD_RIGHT);
        //$this->formattedText = mb_str_pad($text, $fullLength, $padding, STR_PAD_RIGHT, "UTF-8");
        $this->formattedText = "{$this->formattedText}|";
    }

    //|-----------------|METHOD setPaddingRight|-----------------|
    private function setPaddingRight() {
        $text = $this->sourceText;
        $padding = $this->padString;
        $length = $this->remainingLength;
        $fullLength = $this->tempFullLength - 1;
        $this->formattedText = str_pad($text, $fullLength, $padding, STR_PAD_LEFT);
        $this->formattedText = "|{$this->formattedText}";
    }

    //|-------------------|setPadding METHOD|--------------------|
    private function setPadding(PaddingPlace $paddingPlace = PaddingPlace::CENTER) {
        $text = $this->sourceText;
        $padding = $this->padString;
        $length = $this->remainingLength;
        $fullLength = $this->tempFullLength - 2;
        $padType = STR_PAD_BOTH;
        if($paddingPlace != PaddingPlace::CENTER) {
            $fullLength = $this->tempFullLength - 1;
            $padType = STR_PAD_RIGHT;
            if($paddingPlace == PaddingPlace::RIGHT) {
                $padType = STR_PAD_LEFT;
            }
        }
        $this->formattedText = str_pad($text, $fullLength, $padding, $padType);

        //|Unicode Character|------------------------------------|
        if(mb_strlen($this->formattedText) != $fullLength) {
            $this->setRePadding($text, $fullLength, $padding, $paddingPlace);
        }

        //|setPaddingFinalize Format|----------------------------|
        $this->setPaddingFinalize($paddingPlace);
    }

    //|------------------|setRePadding METHOD|-------------------|
    private function setRePadding($text, $fullLength, $padding, PaddingPlace $paddingPlace) {
        $remainingLength = $fullLength - mb_strlen($text);
        if($remainingLength <= 0) {
            return;
        }
        $padLength = $remainingLength;
        if($paddingPlace == PaddingPlace::CENTER) {
            $padLength = ceil($padLength / 2);
        }
        $paddingText = "";
        for($i = 0; $i < $padLength; $i++) {
            $paddingText .= $padding;
        }
        if($paddingPlace == PaddingPlace::CENTER) {
            $paddingText = $paddingText . $text;
            $newPadLength = $remainingLength - $padLength;
            for($i = 0; $i < $newPadLength; $i++) {
                $paddingText .= $padding;
            }
        }  else if($paddingPlace == PaddingPlace::LEFT) {
            $paddingText = $text . $paddingText;
        } else if($paddingPlace == PaddingPlace::RIGHT) {
            $paddingText = $paddingText . $text;
        }
        $this->formattedText = $paddingText;
    }

    //|---------------|setPaddingFinalize METHOD|----------------|
    private function setPaddingFinalize(PaddingPlace $paddingPlace) {
        if($paddingPlace == PaddingPlace::CENTER) {
            $this->formattedText = "|{$this->formattedText}|";
        } else if($paddingPlace == PaddingPlace::LEFT) {
            $this->formattedText = "{$this->formattedText}|";
        } else if($paddingPlace == PaddingPlace::RIGHT) {
            $this->formattedText = "|{$this->formattedText}";
        }
    }
}
?>