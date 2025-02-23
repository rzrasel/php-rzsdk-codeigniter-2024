<?php
namespace RzSDK\Directory\File\Scan;
?>
<?php
class DirectoryFileScan {
    public static function dirToArray($dir, $file_name = ""): array {
        $result = array();
        $cdir = scandir($dir);
        foreach($cdir as $key => $value) {
            if(!in_array($value,array(".", ".."))) {
                if(is_dir($dir . DIRECTORY_SEPARATOR . $value)) {
                    $result[$value] = self::dirToArray($dir . DIRECTORY_SEPARATOR . $value, $file_name);
                } else {
                    /* if($value == $file_name) {
                        $result[] = $value;
                    } */
                    $result[] = $value;
                }
            }
        }
        return $result;
    }
}
?>