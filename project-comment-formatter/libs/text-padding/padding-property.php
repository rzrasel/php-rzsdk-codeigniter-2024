<?php
namespace RzSDK\Padding\Property;
?>
<?php
use RzSDK\String\Utils\TextCase;
use RzSDK\Padding\Utils\PaddingPlace;
?>
<?php
class PaddingProperty {
    protected $sourceText = "";
    protected $fullLength = 64;
    protected $tempFullLength = 0;
    protected $remainingLength = 0;
    protected $padString = "-";
    protected TextCase $textCase = TextCase::UPPER;
    protected PaddingPlace $paddingWings = PaddingPlace::CENTER;
    protected $tabCount = 0;
    protected $tabSpace = 4;
    protected $formattedText = "";
}
?>