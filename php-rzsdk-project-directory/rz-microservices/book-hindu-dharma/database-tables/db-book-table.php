<?php
namespace RzSDK\Database\Space;
?>
<?php
use RzSDK\Database\Book\TblLanguage;
?>
<?php
class DbBookTable {
    public static function language() {
        return TblLanguage::table();
    }

    public static function languageWithPrefix() {
        return TblLanguage::tableWithPrefix();
    }
}
?>