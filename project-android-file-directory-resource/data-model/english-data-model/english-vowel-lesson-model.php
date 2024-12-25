<?php
namespace RzSDK\Data\Model\English;
?>
<?php
class EnglishVowelLessonModel {
    public static function run($dirFileList) {
        $briefList = $dirFileList["brief"];
        self::englishVowelLessonModel($briefList);
    }
    private static function englishVowelLessonModel($briefList) {
        echo "\n";
        echo "\n";
        echo "\n";
        echo "\n";
        echo "@SuppressLint(\"ResourceType\")";
        echo "\n";
        echo "data class EnglishVowelLessonModel(";
        echo "\n";
        echo "val englishLetterModel: ArrayList<LetterAlphabetLessonBaseModel> = arrayListOf(";
        for($row = 0; $row < count($briefList); $row++) {
            $brief = $briefList[$row];
            $brief = substr($brief, 0, strlen($brief) - 4);
            echo "\n";
            echo "LetterAlphabetLessonBaseModel(";
            echo "\n";
            echo "lessonResource = R.drawable.{$brief},";
            echo "\n";
            echo "letterBaseModel = LetterAlphabetBaseModel(),";
            echo "\n";
            echo "),";
        }
        echo "\n";
        echo "),";
        echo "\n";
        echo ")";
        echo "\n";
        echo "\n";
        echo "\n";
        echo "\n";
    }
}
?>