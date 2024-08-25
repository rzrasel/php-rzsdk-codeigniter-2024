<?php
namespace RzSDK\Database\Space;
?>
<?php
use RzSDK\Database\Quiz\TblLanguage;
?>
<?php
class DbQuizTable {
    public static function language() {
        return TblLanguage::table();
    }

    public static function languageWithPrefix() {
        return TblLanguage::tableWithPrefix();
    }
}
?>