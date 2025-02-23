<?php
namespace RzSDK\Libs\File\Rename\Helper;
?>
<?php
use RzSDK\Directory\File\Rename;
use RzSDK\Directory\File\Rename\FileRename;

?>
<?php
class FileRenameHelper {
    function __construct() {
    }

    public function renameTopLevel($fileList, $filePreFix, $preZeros, $filePostFix = "", $directory = ""): void {
        $index = -1;
        foreach($fileList as $key => $value) {
            if(!is_string($key)) {
                $index++;
                $count = $key + 1;
                $oldFile = $directory . "/" . $value;
                //
                $pathinfo = pathinfo($oldFile);
                $extension = $pathinfo["extension"];
                //
                $strLen = strlen($count);
                $strPreZeros = substr($preZeros, $strLen);
                $fileZero = "{$strPreZeros}{$count}";
                //
                $postFix = $filePostFix;
                if(is_array($filePostFix)) {
                    if(empty($filePostFix)) {
                        $postFix = "";
                    }
                    if($index < count($filePostFix)) {
                        $postFix = $filePostFix[$index];
                    }
                }
                //
                $newFile = $directory . "/{$filePreFix}{$fileZero}{$postFix}.{$extension}";
                /* echo "newFile: - {$newFile}";
                echo "<br />"; */
                //
                FileRename::rename($oldFile, $newFile);
            }
        }
    }
}
?>