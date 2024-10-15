<?php
namespace RzSDK\Database\Space;
?>
<?php

use RzSDK\Database\Quiz\TblBookName;
use RzSDK\Database\Quiz\TblLanguage;
use RzSDK\Database\Quiz\TblBookIndex;
use RzSDK\Database\Quiz\TblCharacterTableIndex;
use RzSDK\Database\Quiz\TblCharacterMapping;
?>
<?php
class DbQuizTable {
    public static function language() {
        return TblLanguage::table();
    }

    public static function languageWithPrefix() {
        return TblLanguage::tableWithPrefix();
    }

    public static function bookToken() {
        return TblBookIndex::table();
    }

    public static function bookTokenWithPrefix() {
        return TblBookIndex::tableWithPrefix();
    }

    public static function bookName() {
        return TblBookName::table();
    }

    public static function bookNameWithPrefix() {
        return TblBookName::tableWithPrefix();
    }

    public static function characterTableTokenName() {
        return TblCharacterTableIndex::table();
    }

    public static function characterTableTokenWithPrefix() {
        return TblCharacterTableIndex::tableWithPrefix();
    }

    public static function characterMappingName() {
        return TblCharacterMapping::table();
    }

    public static function characterMappingWithPrefix() {
        return TblCharacterMapping::tableWithPrefix();
    }
}
?>