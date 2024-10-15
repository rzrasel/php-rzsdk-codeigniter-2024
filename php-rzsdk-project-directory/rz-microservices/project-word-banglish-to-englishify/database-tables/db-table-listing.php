<?php
namespace RzSDK\Database\Space;
?>
<?php
use RzSDK\Database\Schema\TblWordMapping;
?>
<?php
class DbTableListing {
    public static function wordMapping() {
        return TblWordMapping::table();
    }

    public static function wordMappingWithPrefix() {
        return TblWordMapping::tableWithPrefix();
    }
}
?>