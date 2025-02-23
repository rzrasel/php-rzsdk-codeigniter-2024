<?php
require_once("include.php");
?>
<?php
use RzSDK\Directory\File\Scan\DirectoryFileScan;
use RzSDK\Data\Model\Arabic\ArabicAlphabetLetterMixedModel;
use RzSDK\Data\Model\Arabic\ArabicVowelLetterModel;
use RzSDK\Data\Model\Arabic\ArabicConsonantLetterModel;
use RzSDK\Data\Model\English\EnglishMathLetterModel;
use RzSDK\Data\Model\English\EnglishConsonantLessonModel;
use RzSDK\Data\Model\English\EnglishVowelLessonModel;
?>
<?php
$directory = "resource";
define("ROOT", dirname(__FILE__) . "/{$directory}");
$fileName = "";
$tree = DirectoryFileScan::dirToArray(ROOT,$fileName);
?>
<?php
//echo "<pre>" . print_r($tree,1)  . "</pre>";
?>
<?php
//ArabicAlphabetLetterMixedModel::run($tree);
ArabicVowelLetterModel::run($tree);
//ArabicConsonantLetterModel::run($tree);
?>
<?php
/* EnglishMathLetterModel::run($tree);
EnglishConsonantLessonModel::run($tree);
EnglishVowelLessonModel::run($tree); */
?>
<?php
/* for (int i = 0; i < 5; i++) {
    for (int j = 0; j < 10; j++) {
        table[i][j] = i * 10 + j + 1; // add a one to get from 0-49 to 1-50
    }
} */
function forLoop($drawableList, $rawList) {
    echo "<br />";
    echo "<br />";
    echo "<br />";
    $columInfo = array(10, 10, 10, 10, 10);
    $columInfo = array(6, 6, 6, 6, 6);
    $rowCount = count($columInfo);
    for($row = 0; $row < $rowCount; $row++) {
        $multiplier = 0;
        if($row > 0) {
            $multiplier = $columInfo[$row];
        }
        for($column = 0; $column < $columInfo[$row]; $column++) {
            $index = ($row * $multiplier) + $column;
            echo "index {$index} - ";
        }
        echo "<br />";
        for($column = $columInfo[$row] - 1; $column >= 0; $column--) {
            $index = ($row * $multiplier) + $column;
            echo "index {$index} - ";
        }
        echo "<br />";
    }
    echo "<br />";
    echo "<br />";
    echo "<br />";
    echo "<br />";
}
?>