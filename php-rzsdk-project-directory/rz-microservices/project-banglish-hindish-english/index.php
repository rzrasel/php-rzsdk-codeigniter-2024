<?php
?>
<?php
require_once("include.php");
?>
<?php
use RzSDK\Utils\String\StringHelper;
?>
<?php
/*$string = "◌्";
//$string = "০১";
$charsCount = mb_strlen($string, 'UTF-8');
for($i = 0; $i < $charsCount; $i++){
    $char = mb_substr($string, $i, 1, 'UTF-8');
    //
    $charAscii = StringHelper::toAscii($char);
    $hexCode = StringHelper::toHex($char);
    $uPlusCode = StringHelper::toHexPlus($char);
    $uCode = StringHelper::toUHex($char);
    //
    if(empty($char)) {
        $char = "-";
    }
    echo "{$char} = {$uPlusCode}";
    echo "<br />";
}
echo "<br />";
echo "<br />";*/


/*$charConsonantShortMap = array(
    "U+0020" => " ",
);*/
/*echo "<pre>";
print_r($charMapList);
echo "</pre>";*/
//
/*$string = "अआइईउऊएऐओऔअंअःऋॠ";
$string = "स्वैरिणी – ब्राह्मणी से इतर वर्ण-क्षत्रिय, वैश्य, शूद्र, जाति की स्त्री।";*/
//
function toBengaliHindi($string) {
    $retVal = "";
    $bengaliHindiLetterMap = new BengaliHindiLetterMap();
    $charMapList = $bengaliHindiLetterMap->getCharacterMapList();
    //
    foreach(mb_str_split($string) as $char) {
        $uPlusCode = StringHelper::toHexPlus($char);
        if(array_key_exists($uPlusCode, $charMapList)) {
            //echo $charMapList[$uPlusCode];
            $retVal .= $charMapList[$uPlusCode];
        } else {
            //echo $uPlusCode;
            $retVal .= $uPlusCode;
        }
    }
    return $retVal;
}
?>
<?php
/*

Evaluation Theories In Hinduism
02:10:53
https://youtu.be/OZfVRYRVddg?t=7851
*/
?>
<?php
class BengaliHindiLetterMap {
    public function getCharacterMapList() {
        $charNumberMap = $this->getNumberMap();
        $charVowelMap = $this->getVowelMap();
        $charConsonantMap = $this->getConsonantMap();
        $charVowelShortMap = $this->getVowelShortMap();
        $charConsonantShortMap = $this->getConsonantShortMap();
        $charMapList = array_merge($charNumberMap, $charVowelMap, $charConsonantMap, $charVowelShortMap, $charConsonantShortMap);
        return $charMapList;
    }

    public function getNumberMap() {
        return array(
            "U+0020" => " ",
            "U+0966" => "০",
            "U+0967" => "১",
            "U+0968" => "২",
            "U+0969" => "৩",
            "U+096A" => "৪",
            "U+096B" => "৫",
            "U+096C" => "৬",
            "U+096D" => "৭",
            "U+096E" => "৮",
            "U+096F" => "৯",
        );
    }

    public function getVowelMap() {
        return array(
            "U+0020" => " ",
            "U+0905" => "অ",
            "U+0906" => "আ",
            "U+0907" => "ই",
            "U+0908" => "ঈ",
            "U+0909" => "উ",
            "U+090A" => "ঊ",
            "U+0960" => "ঋ",
            "U+090B" => "ঋ",
            "U+090F" => "এ",
            "U+0910" => "ঐ",
            "U+0913" => "ও",
            "U+0914" => "ঔ",
        );
    }

    public function getConsonantMap() {
        return array(
            "U+0020" => " ",
            "U+0915" => "ক",
            "U+0916" => "খ",
            "U+0917" => "গ",
            "U+0918" => "ঘ",
            "U+0919" => "ঙ",
            //
            "U+091A" => "চ",
            "U+091B" => "ছ",
            "U+091C" => "জ",
            "U+091D" => "ঝ",
            "U+091E" => "ঞ",
            //
            "U+091F" => "ট",
            "U+0920" => "ঠ",
            "U+0921" => "ড",
            "U+0922" => "ঢ",
            "U+0923" => "ণ",
            //
            "U+0924" => "ত",
            "U+0925" => "থ",
            "U+0926" => "দ",
            "U+0927" => "ধ",
            "U+0928" => "ন",
            //
            "U+092A" => "প",
            "U+092B" => "ফ",
            "U+092C" => "ব",
            "U+092D" => "ভ",
            "U+092E" => "ম",
            //
            "U+092F" => "য",
            "U+0930" => "র",
            "U+0932" => "ল",
            "U+0935" => "ব",
            "U+0936" => "শ",
            //
            "U+0937" => "ষ",
            "U+0938" => "স",
            "U+0939" => "হ",
            "U+095C" => "ড়",
            "U+095D" => "ঢ়",
        );
    }

    public function getVowelShortMap() {
        return array(
            "U+0020" => " ",
            "U+093E" => "া",
            "U+093F" => "ি",
            "U+0940" => "ী",
            "U+0941" => "ু",
            "U+0942" => "ূ",
            //
            "U+0943" => "ৃ",
            //
            "U+0945" => "ে",
            "U+0946" => "ে",
            "U+0947" => "ে",
            //
            "U+0948" => "ৈ",
            //
            "U+0949" => "ো",
            "U+094A" => "ো",
            "U+094B" => "ো",
            //
            "U+094C" => "ৌ",
            //
            "U+094D" => "্",
            "U+0902" => "(য়)",
            //
            "U+0964" => "।",
            "U+2013" => "–",
            "U+002D" => "–",
            "U+002C" => ",",
        );
    }

    public function getConsonantShortMap() {
        return array();
    }
}
?>
<?php
/*echo "<br />";
echo $string;
echo "<br />";
$string = toBengaliHindi($string);
echo $string;
echo "<br />";*/
$input = "";
$output = "";
if(!empty($_POST)) {
    $input = $_POST["input"];
    //$output = $_POST["output"];
    if(!empty($input)) {
        $output = toBengaliHindi($input);
        //echo $output;
    }
}
?>
<table width="100%;">
    <tr>
        <td>
            <form action="<?= $_SERVER["PHP_SELF"]; ?>" method="POST">
                <table width="100%;">
                    <tr>
                        <td width="100px;">Input String: </td>
                        <td><input type="text" name="input" value="<?= $input; ?>" style="width: 400px;"></td>
                    </tr>
                    <tr>
                        <td height="20px;"></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Output String: </td>
                        <td><textarea rows="4"  style="width: 400px;" readonly="readonly"><?= $output; ?></textarea></td>
                    </tr>
                    <tr>
                        <td height="20px;"></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td><input type="submit"></td>
                    </tr>
                </table>
            </form>
        </td>
    </tr>
</table>
