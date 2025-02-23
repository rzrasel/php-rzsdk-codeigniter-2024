<?php
namespace RzSDK\Directory\File\Rename;
?>
<?php
class FileRename {
    public static function rename($from, $to): void {
        rename($from, $to);
    }
}
?>