<?php
namespace RzSDK\String\To\Word\Extension;
?>
<?php
use RzSDK\Utils\String\StringHelper;
use RzSDK\Log\DebugLog;
?>
<?php
class StringToWordExtension {
    public static function toStringToWord($text, $isLowerCase = true) {
        $text = trim($text);
        $text = StringHelper::toSingleSpace($text);
        //
        if(empty($text)) {
            return array();
        }
        $explodeWordList = explode(" ", $text);
        $wordList = array();
        foreach($explodeWordList as $word) {
            $word = trim($word);
            if(!empty($word)) {
                if($isLowerCase) {
                    $word = strtolower($word);
                }
                $wordList[] = $word;
            }
        }
        return $wordList;
    }

    public static function toRemovePunctuation($text) {
        if(is_array($text)) {
            $text = implode(" ", $text);
        }
        $text = trim($text);
        $text = StringHelper::toSingleSpace($text);
        //
        $delimiterList = self::getDelimiterList();
        foreach($delimiterList as $delimiter) {
            $text = str_replace($delimiter, "", $text);
            //$text = preg_replace($delimiter, "", $text);
        }
        $text = self::toStringToWord($text, false);
        return $text;
    }

    public static function getDelimiterList() {
        return array(
            ".", "!", "।", "?", ",", ";", "'", "\"",
            "‘", "’", "❛", "❜", "“", "”",
            "(", ")", "{", "}", "[", "]",
        );
    }
}
?>