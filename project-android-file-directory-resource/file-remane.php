<?php
require_once("include.php");
?>
<?php
use RzSDK\Directory\File\Scan\DirectoryFileScan;
use RzSDK\Libs\File\Rename\Helper\FileRenameHelper;
?>
<?php
$directory = "resource";
define("ROOT", dirname(__FILE__) . "/{$directory}");
$fileName = "";
$tree = DirectoryFileScan::dirToArray(ROOT,$fileName);
?>
<?php
echo "<pre>" . print_r($tree,1)  . "</pre>";
?>
<?php
/* $fileList = array();
for($i = 1; $i < 22; $i++) {
    $fileList[] = "alpha_en_consonant_{$i}_type_1.mp3";
} */
$fileList = $tree["rename"];
echo "<pre>" . print_r($fileList,1)  . "</pre>";
$directory = "{$directory}/rename";
//
$preZeros = "00";
$filePreFix = "letter_ar_alphabet_";
//$filePreFix = "audio_ar_alphabet_";
$filePostFix = arabicFilePostFix();
/**-/
$fileRenameHelper = new FileRenameHelper();
$fileRenameHelper->renameTopLevel(
    $fileList,
    $filePreFix,
    $preZeros,
    $filePostFix,
    $directory
);
/**/
?>
<?php
function arabicFilePostFix(): array {
    return array(
        "_vowel_alif", "_consonant_baa", "_consonant_taa", "_consonant_tha",
        "_consonant_jim", "_consonant_ha", "_consonant_kho", "_consonant_dal",
        "_consonant_zhal", "_consonant_ra", "_consonant_za", "_consonant_sin",
        "_consonant_syin", "_consonant_shod", "_consonant_dhod", "_consonant_tho",
        "_consonant_dho", "_consonant_ain", "_consonant_ghain", "_consonant_faa",
        "_consonant_khob", "_consonant_kaf", "_consonant_lam", "_consonant_mim",
        "_consonant_nun", "_vowel_wau", "_consonant_haa", "_consonant_hamza",
        "_vowel_ya", "_vowel_yaa",
    );
}
?>