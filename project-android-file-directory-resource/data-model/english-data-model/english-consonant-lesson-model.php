<?php
namespace RzSDK\Data\Model\English;
?>
<?php
class EnglishConsonantLessonModel {
    public static function run($dirFileList) {
        $briefList = $dirFileList["brief"];
        self::englishConsonantLessonModel($briefList);
    }

    private static function englishConsonantLessonModel($briefList) {
        echo "\n";
        echo "\n";
        echo "\n";
        echo "\n";
        echo "@SuppressLint(\"ResourceType\")";
        echo "\n";
        echo "data class EnglishConsonantLessonModel(";
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