<?php
namespace RzSDK\Data\Model\Arabic;
?>
<?php
class ArabicAlphabetLetterMixedModel {
    public static function run($dirFileList) {
        $drawableList = $dirFileList["drawable"];
        $rawList = $dirFileList["raw"];
        self::arabicAlphabetLetterMixedModel($drawableList, $rawList);
    }

    private static function arabicAlphabetLetterMixedModel($drawableList, $rawList) {
        echo "\n";
        echo "\n";
        echo "\n";
        echo "\n";
        $rowCount = 1;
        $columInfo = array(6, 6, 6, 6, 6);
        $rowCount = count($columInfo);
        echo "@SuppressLint(\"ResourceType\")";
        echo "\n";
        echo "data class ArabicAlphabetLetterMixedModel(";
        echo "\n";
        echo "@SuppressLint(\"ResourceType\") val alphabetLetterMixedModel: ";
        echo "\n";
        echo "ArrayList<ArrayList<LetterAlphabetBaseModel>> = arrayListOf(";
        $counter = 0;
        for($row = 0; $row < $rowCount; $row++) {
            echo "\n";
            echo "arrayListOf(";
            $multiplier = 0;
            if($row > 0) {
                $multiplier = $columInfo[$row];
            }
            for($column = $columInfo[$row] - 1; $column >= 0; $column--) {
                $index = $row * 3 + $column;
                $index = $counter;
                $counter++;
                /* echo $index;
                echo " "; */
                $index = ($row * $multiplier) + $column;
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