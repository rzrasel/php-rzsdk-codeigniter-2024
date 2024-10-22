<?php
namespace RzSDK\Activity\Formatter;
?>
<?php
use RzSDK\String\Utils\TextPadding;
use function RzSDK\Padding\Utils\getPaddingPlaceByValue;
use function RzSDK\String\Utils\getTextCaseByValue;

?>
<?php
//|---------------|CLASS CommentFormatterActivity|---------------|
class CommentFormatterActivity {
    //|---------------------|VARIABLE SCOPE|---------------------|
    private $formattedText = "";

    //|-------------------|METHOD __CONSTRUCT|-------------------|
    public function __construct() {}

    //|---------------------|METHOD EXECUTE|---------------------|
    public function execute($postedDataSet) {
        //|Print Post Data|--------------------------------------|
        /*echo "<pre>";
        print_r($postedDataSet);
        echo "</pre>";*/

        //|Set Post Data Into Variable|--------------------------|
        $totalCharacters = $postedDataSet["total_characters"];
        $totalTabs = $postedDataSet["total_tabs"];
        $paddingPlace = $postedDataSet["padding_place"];
        $paddingCharacter = $postedDataSet["padding_character"];
        $sourceText = $postedDataSet["comment_text"];
        $textCase = $postedDataSet["text_case"];

        //|Convert String Value To Enum Value|-------------------|
        $textCase = getTextCaseByValue($textCase);
        $paddingPlace = getPaddingPlaceByValue($paddingPlace);

        //|Initialize And Setup TextPadding Class Object|--------|
        $textPadding = new TextPadding();
        $textPadding->setSourceText($sourceText)
            ->setFullLength($totalCharacters)
            ->setNumberOfTabs($totalTabs)
            ->setPaddingCharacter($paddingCharacter)
            ->setTextCase($textCase)
            ->setPaddingPlace($paddingPlace);
        $textPadding->execute();

        //|Set formattedText|------------------------------------|
        $this->formattedText = $textPadding->getFormattedText();
    }

    //|----------------|METHOD getFormattedText|-----------------|
    public function getFormattedText() {
        return $this->formattedText;
    }
}
?>