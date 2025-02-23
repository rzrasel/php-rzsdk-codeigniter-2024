<?php
namespace RzSDK\Database\Space;
?>
<?php
use RzSDK\Database\Schema\TblSubjectInfo;
?>
<?php
class DbTableschema {
    public static function subjectInfo() {
        return TblSubjectInfo::table();
    }

    public static function subjectInfoWithPrefix() {
        return TblSubjectInfo::tableWithPrefix();
    }
}
?>