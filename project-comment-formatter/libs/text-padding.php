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
require_once("text-padding/set-text-case.php");
require_once("text-padding/set-padding-edge.php");
?>
<?php
use RzSDK\Padding\Property\PaddingProperty;
use RzSDK\Padding\Property\SetSourceText;
use RzSDK\Padding\Property\SetFullLength;
use RzSDK\Padding\Property\SetNumberOfTabs;
use RzSDK\Padding\Property\SetTextCase;
use RzSDK\Padding\Property\SetPaddingEdge;
use RzSDK\Padding\Utils\PaddingPlace;
?>
<?php
class TextPadding extends PaddingProperty {
    use SetSourceText, SetFullLength;
    use SetNumberOfTabs;
    use SetTextCase, SetPaddingEdge;

    public function execute() {
        $this->sourceText = StringUtils::removeWhitespace($this->sourceText);
        $this->sourceText = StringUtils::toCaseConversion($this->sourceText, $this->textCase);
        $this->sourceText = "|{$this->sourceText}|";
        //echo $this->sourceText;
        $this->calculatePadding();
    }

    public function getFormattedText() {
        return $this->formattedText;
    }

    private function calculatePadding() {
        //
        $this->tempFullLength = $this->fullLength;
        $this->tempFullLength = $this->tempFullLength - ($this->tabCount * $this->tabSpace);
        //
        $this->remainingLength = $this->fullLength - strlen($this->sourceText);
        $this->remainingLength = $this->remainingLength - ($this->tabCount * $this->tabSpace);
        //
        if($this->remainingLength < 0) {
            $this->remainingLength = 0;
        }
        /*echo "<br />";
        echo $this->remainingLength;*/
        $this->addPadding();
    }

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

    private function setPaddingLeft() {
        $text = $this->sourceText;
        $padding = $this->padString;
        $length = $this->remainingLength;
        $fullLength = $this->tempFullLength - 1;
        $this->formattedText = str_pad($text, $fullLength, $padding, STR_PAD_RIGHT);
        $this->formattedText = "{$this->formattedText}|";
    }

    private function setPaddingRight() {
        $text = $this->sourceText;
        $padding = $this->padString;
        $length = $this->remainingLength;
        $fullLength = $this->tempFullLength - 1;
        $this->formattedText = str_pad($text, $fullLength, $padding, STR_PAD_LEFT);
        $this->formattedText = "|{$this->formattedText}";
    }

    private function setPaddingBoth() {
        $text = $this->sourceText;
        $padding = $this->padString;
        $length = $this->remainingLength;
        $fullLength = $this->tempFullLength - 2;
        $this->formattedText = str_pad($text, $fullLength, $padding, STR_PAD_BOTH);
        $this->formattedText = "|{$this->formattedText}|";
    }
}
?>