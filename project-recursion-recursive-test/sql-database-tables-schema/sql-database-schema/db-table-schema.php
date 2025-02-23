<?php
namespace RzSDK\Database\Schema;
?>
<?php
use RzSDK\Database\Schema\TblParentChildInfo;
?>
<?php
class DbTableSchema {
    public static function parentChildInfo() {
        return TblParentChildInfo::table();
    }

    public static function parentChildInfoWithPrefix() {
        return TblParentChildInfo::tableWithPrefix();
    }
}
?>