<?php
/*$strText = "Hello World";
$padText = "-";
$padString = str_pad($strText, 20, $padText, STR_PAD_LEFT);
echo "" . $padString;*/
/*echo "<br />";
echo "<br />";
echo "\n";
echo "\n";*/
$strText = "Hello World";
$textPadding = new TextPadding();
$textPadding->withPad("-")->withPadFullLength(64)
->withFullLeftText("|")->withFullRightText("|")
->withPadLeftText("|")->withPadRightText("|")
->withTabCount(0)->withTabSpace(4);
$textPadding->withCase(TextCase::LowerCase);
$textPadding->generate($strText);
$paddingString = $textPadding->getPadString();
//echo $paddingString;
$textPadding->logPrint($paddingString);
$textPadding->logPrint(strlen($paddingString));
/*echo "<br />";
echo "<br />";
echo "\n";
echo "\n";*/
$strText = "World Hello aklfalsf";
$textPadding->withCase(TextCase::UpperCase);
$textPadding->generate($strText);
$paddingString = $textPadding->getPadString();
//echo $paddingString;
$textPadding->logPrint($paddingString);
$textPadding->logPrint(strlen($paddingString));
//
//
$strText = "Class variable Scope Start";
$textPadding->generate($strText);
$paddingString = $textPadding->getPadString();
//echo $paddingString;
$textPadding->logPrint($paddingString);
$textPadding->logPrint(strlen($paddingString));
//
//
$strText = "Class variable Scope End";
$textPadding->generate($strText);
$paddingString = $textPadding->getPadString();
//echo $paddingString;
$textPadding->logPrint($paddingString);
$textPadding->logPrint(strlen($paddingString));
//
//
$strText = "Padding left and right text";
$textPadding->generate($strText);
$paddingString = $textPadding->getPadString();
//echo $paddingString;
$textPadding->logPrint($paddingString);
$textPadding->logPrint(strlen($paddingString));
//
//
$strText = "Final return text";
$textPadding->generate($strText);
$paddingString = $textPadding->getPadString();
//echo $paddingString;
$textPadding->logPrint($paddingString);
$textPadding->logPrint(strlen($paddingString));
//
//
$strText = "Setup padding text/symble";
$textPadding->withTabCount(1);
$textPadding->generate($strText);
$paddingString = $textPadding->getPadString();
//echo $paddingString;
$textPadding->logPrint($paddingString);
$textPadding->logPrint(strlen($paddingString));
//
$strText = "Setup padding text full/total length";
$textPadding->generate($strText);
$paddingString = $textPadding->getPadString();
//echo $paddingString;
$textPadding->logPrint($paddingString);
$textPadding->logPrint(strlen($paddingString));
//
//
$textPadding->logPrint("", "\n", 4);
?>
<?php
class TextPadding {
    //|-----------------|CLASS VARIABLE SCOPE START|-----------------|
    private $sourceString = "";
    private $fullPadLength = 64;
    private $defaultFullPadLength = 64;
    private $padString = "-";
    private $textCase = TextCase::AlikeCase;
    //|----------------|PADDING LEFT AND RIGHT TEXT|-----------------|
    private $fullLeftText = "";
    private $fullRightText = "";
    private $padLeftText = "";
    private $padRightText = "";
    private $tabCount = 0;
    private $tabSpace = 4;
    //|---------------------|FINAL RETURN TEXT|----------------------|
    private $retString = "";
    //|------------------|CLASS VARIABLE SCOPE END|------------------|
    public function withPad($padString) {
        //|---------------|SETUP PADDING TEXT/SYMBLE|----------------|
        $this->padString = $padString;
        return $this;
    }
    public function withPadFullLength($padFullLength) {
        //|----------|SETUP PADDING TEXT FULL/TOTAL LENGTH|----------|
        $this->fullPadLength = $padFullLength;
        $this->defaultFullPadLength = $padFullLength;
        return $this;
    }
    public function withCase($textCase) {
        $this->textCase = $textCase;
        return $this;
    }
    public function withFullLeftText($fullLeftText) {
        $this->fullLeftText = $fullLeftText;
        return $this;
    }
    public function withFullRightText($fullRightText) {
        $this->fullRightText = $fullRightText;
        return $this;
    }
    public function withPadLeftText($padLeftText) {
        $this->padLeftText = $padLeftText;
        return $this;
    }
    public function withPadRightText($padRightText) {
        $this->padRightText = $padRightText;
        return $this;
    }
    public function withTabCount($tabCount) {
        $this->tabCount = $tabCount;
        return $this;
    }
    public function withTabSpace($tabSpace) {
        $this->tabSpace = $tabSpace;
        return $this;
    }
    public function getPadString() {
        return $this->fullLeftText . $this->retString . $this->fullRightText;
    }
    public function generate($sourceString) {
        $this->sourceString = $sourceString;
        $this->prepear();
        $this->retString = str_pad($this->sourceString, $this->fullPadLength, $this->padString, STR_PAD_BOTH);
    }
    private function prepear() {
        $this->fullPadLength = $this->defaultFullPadLength;
        if($this->textCase == TextCase::UpperCase) {
            $this->sourceString = strtoupper($this->sourceString);
        } else if($this->textCase == TextCase::LowerCase) {
            $this->sourceString = strtolower($this->sourceString);
        }
        $this->sourceString = $this->padLeftText . $this->sourceString . $this->padRightText;
        $spaceCount = $this->tabCount * $this->tabSpace;
        //$this->fullPadLength = $this->fullPadLength - (strlen($this->fullLeftText) + strlen($this->fullRightText) + strlen($this->padLeftText)  + strlen($this->padRightText));
        $this->fullPadLength = $this->fullPadLength - (strlen($this->fullLeftText) + strlen($this->fullRightText) + $spaceCount);
    }
    public function logPrint($logMessage, $lineBreak = "\n", $totalBreak = 1) {
        $lineBreakText = $lineBreak;
        $breakCount = $totalBreak;
        for($index = 0; $index < $breakCount; $index++) {
            echo $lineBreakText;
        }
        //echo "\n";
        echo $logMessage;
        //echo "\n";
        for($index = 0; $index < $breakCount; $index++) {
            echo $lineBreakText;
        }
    }
}
?>
<?php
abstract class TextCase {
    const AlikeCase = 0;
    const UpperCase = 1;
    const LowerCase = 2;
}
?>
