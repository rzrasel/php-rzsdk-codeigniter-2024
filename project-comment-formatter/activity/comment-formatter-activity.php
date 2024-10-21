<?php
namespace RzSDK\Activity\Formatter;
?>
<?php
use RzSDK\String\Utils\TextPadding;
use RzSDK\Padding\Utils\PaddingPlace;
use RzSDK\String\Utils\TextCase;
use function RzSDK\Padding\Utils\getPaddingPlaceByValue;
use function RzSDK\String\Utils\getTextCaseByValue;

?>
<?php
class CommentFormatterActivity {
    private $formattedText = "";
    public function __construct() {}

    public function execute($postedDataSet) {
        /*echo "<pre>";
        print_r($postedDataSet);
        echo "</pre>";*/
        //
        $totalCharacters = $postedDataSet["total_characters"];
        $totalTabs = $postedDataSet["total_tabs"];
        $paddingPlace = $postedDataSet["padding_place"];
        $sourceText = $postedDataSet["comment_text"];
        $textCase = $postedDataSet["text_case"];
        //
        $textCase = getTextCaseByValue($textCase);
        $paddingPlace = getPaddingPlaceByValue($paddingPlace);
        //
        $textPadding = new TextPadding();
        $textPadding->setSourceText($sourceText)
            ->setFullLength($totalCharacters)
            ->setNumberOfTabs($totalTabs)
            ->setTextCase($textCase)
            ->setPaddingPlace($paddingPlace);
        $textPadding->execute();
        //echo ceil(5 / 2);
        $this->formattedText = $textPadding->getFormattedText();
    }

    public function getFormattedText() {
        return $this->formattedText;
    }
}
?>