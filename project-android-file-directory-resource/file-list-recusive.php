<?php
require_once("include.php");
?>
<?php
use RzSDK\Directory\File\Scan\DirectoryFileScan;
use RzSDK\Libs\File\Rename\Helper\FileRenameHelper;
?>
<?php
$directory = "resource";
define("ROOT", dirname(__FILE__) . "/{$directory}");
$fileName = "";
$tree = DirectoryFileScan::dirToArray(ROOT,$fileName);
?>
<?php
echo "<pre>" . print_r($tree,1)  . "</pre>";
?>
<?php
$fileList = array();
for($i = 1; $i < 22; $i++) {
    $fileList[] = "alpha_en_consonant_{$i}_type_1.mp3";
}
//$fileList = $tree["rename"];
echo "<pre>" . print_r($fileList,1)  . "</pre>";
$directory = "{$directory}/rename";
$fileRenameHelper = new FileRenameHelper();
//$fileRenameHelper->renameTopLevel($fileList, "letter_en_consonant_", "00", $directory);
?>
<?php
class ResourceFile {
    public $brief = "brief";
    public $drawable = "drawable";
    public $raw = "raw";
}
?>
<?php
enum ResourceType: string {
    case BRIEF = "brief";
    case DRAWABLE = "drawable";
    case DRAWABLE_1 = "drawable-1";
    case RAW  = "raw";
    case EMPTY  = "empty";
    public static function getByValue($value): self {
        foreach(self::cases() as $props) {
            /* if ($case->name === $enumName) {
                return $case;
            } */
            if($props->value === $value) {
                return $props;
            }
        }
        return self::EMPTY;
    }

    public function getDescription(): string {
        return match($this) {
            self::BRIEF         => "drawable",
            self::DRAWABLE      => "drawable",
            self::DRAWABLE_1    => "drawable",
            self::RAW           => "raw",
        };
    }
}
?>
<?php
function dirFileListToArray($dirList): array {
    $dirFileList = array();
    foreach($dirList as $keyOne => $valueOne) {
        if(is_array($valueOne)) {
            //$fileList = new FileList();
            foreach($valueOne as $keyTwo => $valueTwo) {
                $fileList = new ResourceFile();
                $fileList->brief = null;
                $fileList->drawable = null;
                $fileList->raw = null;
                if(!array_key_exists($keyTwo, $dirFileList)) {
                    $dirFileList[$keyTwo] = $fileList;
                }
                if(!is_string($keyTwo)) {
                    $resourceType = ResourceType::getByValue($keyOne);
                    if($resourceType == ResourceType::DRAWABLE) {
                        //$fileList->drawable = $valueTwo;
                        $dirFileList[$keyTwo]->drawable = $valueTwo;
                        /* echo $resourceType->getDescription();
                        echo "<br />"; */
                    } else if($resourceType == ResourceType::DRAWABLE_1) {
                        //$fileList->drawable = $valueTwo;
                        $dirFileList[$keyTwo]->drawable = $valueTwo;
                        /* echo $resourceType->getDescription();
                        echo "<br />"; */
                    } else if($resourceType == ResourceType::RAW) {
                        //$fileList->raw = $valueTwo;
                        $dirFileList[$keyTwo]->raw = $valueTwo;
                    }
                }
                //$dirFileList[] = $fileList;
            }
            //$dirFileList[] = $fileList;
        }
    }
    //echo "<pre>" . print_r($dirFileList,1)  . "</pre>";
    return $dirFileList;
}
dirFileListToArray($tree);
?>
<?php
/* $directory = "scan";
define("ROOT", dirname(__FILE__) . "/{$directory}");
$file_name = 'template.html';
$tree = dirToArray(ROOT,$file_name);
echo "<pre>".print_r($tree,1)."</pre>";
//echo json_encode($tree);
$filePreFix = "ad-";
$preZeros = "00";
foreach($tree as $key => $value) {
    if(!is_string($key)) {
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
        $newFile = $directory . "/{$filePreFix}{$fileZero}.{$extension}";
        /-* echo "newFile: - {$newFile}";
        echo "<br />"; *-/
        //
    }
}
echo "End"; */
?>