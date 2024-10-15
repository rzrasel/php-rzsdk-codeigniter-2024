<?php
namespace RzSDK\DatabaseSpace;
?>
<?php
use RzSDK\Database\Word\TblLanguage;
use RzSDK\Database\Word\DictionaryWord;
use RzSDK\Database\Word\Meaning\DictionaryWordMeaning;
?>
<?php
class DbWordTable {
    public static $language = "tbl_language";
    public static $dictionaryWord = "dictionary_word";

    public static function language() {
        return TblLanguage::table();
    }

    public static function languageWithPrefix() {
        return TblLanguage::tableWithPrefix();
    }

    public static function dictionaryWord() {
        return DictionaryWord::table();
    }

    public static function dictionaryWordWithPrefix() {
        return DictionaryWord::tableWithPrefix();
    }

    public static function dictionaryWordMeaning() {
        return DictionaryWordMeaning::table();
    }

    public static function dictionaryWordMeaningWithPrefix() {
        return DictionaryWordMeaning::tableWithPrefix();
    }
}
?>