<?php
namespace RzSDK\Data\Model\English;
?>
<?php
class EnglishMathLetterModel {
    public static function run($dirFileList) {
        $drawableList = $dirFileList["drawable"];
        $rawList = $dirFileList["raw"];
        self::englishMathLetterModel($drawableList, $rawList);
    }

    private static function englishMathLetterModel($drawableList, $rawList) {
        echo "\n";
        echo "\n";
        echo "\n";
        echo "\n";
        $rowCount = 1;
        $columInfo = array(3, 4, 3);
        $rowCount = count($columInfo);
        echo "@SuppressLint(\"ResourceType\")";
        echo "\n";
        echo "data class EnglishMathLetterModel(";
        echo "\n";
        echo "@SuppressLint(\"ResourceType\") val englishMathModel: ";
        echo "\n";
        echo "ArrayList<ArrayList<LetterAlphabetBaseModel>> = arrayListOf(";
        $counter = 0;
        for($row = 0; $row < $rowCount; $row++) {
            echo "\n";
            echo "arrayListOf(";
            for($column = 0; $column < $columInfo[$row]; $column++) {
                $index = $row * 3 + $column;
                $index = $counter;
                $counter++;
                /* echo $index;
                echo " "; */
                $drawable = $drawableList[$index];
                $drawable = substr($drawable, 0, strlen($drawable) - 4);
                $raw = $rawList[$index];
                $raw = substr($raw, 0, strlen($raw) - 4);
                echo "\n";
                echo "LetterAlphabetBaseModel(";
                echo "\n";
                echo "normalResource = R.drawable.{$drawable},";
                echo "\n";
                echo "audio = R.raw.{$raw},";
                echo "\n";
                echo "),";
            }
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